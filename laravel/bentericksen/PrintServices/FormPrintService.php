<?php

namespace Bentericksen\PrintServices;

use Carbon\Carbon;

class FormPrintService
{
    protected $business;
    protected $form;
    protected $filename;
    protected $folder;
    protected $html;

    public function __construct( $business, $form, $filename )
    {
        $this->business = $business;
        $this->form = $form;
        $this->filename = $filename;

        $now = Carbon::now();
        $year = $now->format('Y');
        $month = $now->format('m');
        $this->folder = "bentericksen/forms/{$year}/{$month}/";
    }

    public function generate()
    {
        $snappy = \App::make('snappy.pdf');
        $options = [
            'margin-top'    => 30,
            'margin-right'  => 5,
            'margin-bottom' => 25,
            'margin-left'   => 5,
        ];

        $path = storage_path($this->folder);

        if (!file_exists($path)) {
            \File::makeDirectory($path, 0775, true);
        }
        $this->buildHTML();
        $snappy->setOption('header-spacing', '5');
        $snappy->setOption('header-html', $this->getHeader());
        $snappy->setOption('footer-spacing', '10');
        $snappy->setOption('footer-html', $this->getFooter() );

        $snappy->generateFromHtml($this->getHTML(), storage_path($this->folder . $this->filename), $options );

        $this->form->folder = $this->folder;
        $this->form->file = $this->filename;
        $this->form->save();
    }

    public function getFooter()
    {

        $path = str_replace('footer.blade.php', 'footer.html', view('pdf.forms.footer')->getPath());
        if(! file_exists( $path ) OR 606478 > (strtotime("1 week") - filemtime( $path )) )
        {
            $temp = view('pdf.forms.footer')->with('form', $this->form)->render();
            file_put_contents( $path, $temp );
        }

        return $path;
    }

    public function getHTML()
    {
        $html = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8');
        return $html;
    }

    private function buildHTML()
    {
        $results = \DB::select( \DB::raw("SELECT forms.*, form_templates.name FROM forms, form_templates WHERE forms.id = :form_id AND forms.template_id = form_templates.id"), [
           'form_id' => $this->form->id,
         ]);

        if( count($results) > 0 )
        {
            $body = view('pdf.forms.form')->with('form', $results[0])
                                        ->render();
        } else {
            $body = "";
        }

        $this->html = $body;
    }

    private function getHeader()
    {
        $form = \App\FormTemplate::findOrFail( $this->form->template_id );

        $header = view('pdf.forms.header')->with('form', $form)->render();
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_random() . ".html";
        file_put_contents( $path, $header);

        return $path;
    }
}
