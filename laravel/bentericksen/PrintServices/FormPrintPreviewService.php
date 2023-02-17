<?php

namespace Bentericksen\PrintServices;

use Carbon\Carbon;

class FormPrintPreviewService
{
    protected $business;
    protected $form;
    protected $filename;
    protected $folder;
    protected $html;

    public function __construct( $form, $filename )
    {
        $this->form = $form;
        $this->filename = $filename;
        $now = Carbon::now();
    }

    public function generate()
    {
        $snappy = \App::make('snappy.pdf');
        $options = [
            'margin-top'    => 10,
            'margin-right'  => 5,
            'margin-bottom' => 25,
            'margin-left'   => 5,
        ];

        $this->buildHTML();
        $snappy->setOption('footer-spacing', '10');
        $snappy->setOption('footer-html', $this->getFooter() );
        $snappy->generateFromHtml($this->getHTML(), sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->filename, $options );
    }

    public function getFooter()
    {
        $path = str_replace('footer.blade.php', 'footer.html', view('pdf.forms.footer')->getPath());
        if(! file_exists( $path ) OR 606478 > (strtotime("1 week") - filemtime( $path )) )
        {
            $temp = view('pdf.forms.footer')
                        ->with('form', $this->form)
                        ->render();
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
        $body = view('pdf.forms.form')
                    ->with('form', $this->form)
                    ->render();
        $this->html = $body;
    }
}
