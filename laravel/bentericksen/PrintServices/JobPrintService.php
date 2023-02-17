<?php

namespace Bentericksen\PrintServices;

use Storage;

class JobPrintService
{
    protected $job;
    protected $filename;

    public function __construct( $job, $filename )
    {
        $this->job = $job;
        $this->filename = $filename;

        $this->buildHTML();
    }

    public function generate()
    {
        $snappy = \App::make('snappy.pdf');
        $options = [
            'margin-top'    => 25,
            'margin-right'  => 25,
            'margin-bottom' => 25,
            'margin-left'   => 25,
        ];

        $header = view('pdf.jobs.header')->with('job', $this->job)->render();

        Storage::put( $this->filename . '_header.html', $header);

        $snappy->setOption('header-html', storage_path('app/' . $this->filename . '_header.html'));
        $snappy->setOption('footer-html', base_path('resources/views/pdf/jobs/footer.html'));
        $snappy->setOption('footer-spacing', '10');
        $snappy->generateFromHtml($this->getHTML(), storage_path('bentericksen/temp/' . $this->filename ), $options );
    }

    public function getHTML()
    {
        $html = mb_convert_encoding($this->buildHTML(), 'HTML-ENTITIES', 'UTF-8');

        return $html;
    }

    private function buildHTML()
    {
        $body = view('pdf.jobs.jobs')
                    ->with('job', $this->job)
                    ->render();

        return $body;
        $this->html = $body;
    }
}
