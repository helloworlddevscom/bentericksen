<?php

use App\Business;
use App\Form;
use App\FormTemplate;
use Bentericksen\PrintServices\FormPrintService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UpdateBusinessFormsRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $businesses = Business::where('state', 'MT')->get();

        foreach ($businesses as $business) {
            $this->resetForms($business);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    protected function resetForms($business)
    {
        Form::where('business_id', $business->id)->delete();

        $formTemplate = new FormTemplate();

        $templates = $formTemplate->getApplicableTemplates($business, true);
        $forms = [];

        foreach ($templates as $temp) {
            $data = [
                'template_id'   => $temp->id,
                'description'   => $temp->description,
                'status'        => $temp->status,
                'edited'        => 'no',
                'folder'        => $temp->getUploadPath().DIRECTORY_SEPARATOR,
            ];

            if ($temp->file_name) {
                $data['file'] = $temp->file_name;
            } else {
                $data['file'] = 'temp';
            }

            array_push($forms, new Form($data));

            if ($data['file'] == 'temp') {
                $filename = Str::random(40);
                $formService = new FormPrintService($business, $business->forms, $filename);
                $formService->generate();
            }
        }

        $business->forms()->saveMany($forms);
        $business->save();
    }
}
