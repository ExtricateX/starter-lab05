<?php

class Admin extends Application {

    function __construct() {
        parent::__construct();
        $this->load->helper('formfields');
    }

    function index() {
        $this->data['title'] = 'Quotations Maintenance';
        $this->data['quotes'] = $this->quotes->all();
        $this->data['pagebody'] = 'admin_list';
        $this->render();
    }

    function present($quote) {
        $this->data['fid'] = makeTextField('ID#', 'id', $quote->id, "Unique quote identifier, system-assigned",10,10,true);
        $this->data['fwho'] = makeTextField('Author', 'who', $quote->who);
        $this->data['fmug'] = makeTextField('Picture', 'mug', $quote->mug);
        $this->data['fwhat'] = makeTextArea('The Quote', 'what', $quote->what);
        $this->data['pagebody'] = 'quote_edit';
        $this->data['fsubmit'] = makeSubmitButton('Process Quote', "Click here to validate the quotation data", '');

        $message = '';
        if (count($this->errors) > 0) {
            foreach ($this->errors as $booboo)
                $message .= $booboo . '<br>';
        }
        $this->data['message'] = $message;

        $this->render();
    }

    function confirm()
    {
        $record = $this->quotes->create();
        $record->id = $this->input->post('id');
        $record->who = $this->input->post('who');
        $record->mug = $this->input->post('mug');
        $record->what = $this->input->post('what');

        if (empty($record->who))
            $this->errors[] = 'You must specify an author.';
        if (strlen($record->what) < 20)
            $this->errors[] = 'A quotation must be at least 20 characters long.';

        if (count($this->errors) > 0) {
            $this->present($record);
            return; // make sure we don't try to save anything
        }

        if (empty($record->id)) $this->quotes->add($record);
        else $this->quotes->update($record);
        redirect('/admin');
    }

    function add() {
        $quote = $this->quotes->create();
        $this->present($quote);
    }
}