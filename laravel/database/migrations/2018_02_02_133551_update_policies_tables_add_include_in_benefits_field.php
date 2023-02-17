<?php

use App\Policy;
use App\PolicyTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePoliciesTablesAddIncludeInBenefitsField extends Migration
{
    /**
     * Existing Policies that are to be included in the Benefits summary.
     * @var array
     */
    protected $policy_templates = [
        1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20, 21, 22, 49, 50, 51, 52, 53, 54, 55, 57, 58, 59, 60, 61, 62, 63, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 125, 126, 127, 128, 130, 131, 132, 133, 134, 135, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149, 150, 151, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165, 166, 168, 169, 170, 171, 172, 173, 174, 176, 177, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 197, 198, 199, 202, 203, 204, 205, 206, 207, 208, 210, 211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 276, 277, 278, 279, 280, 281, 282, 283, 301, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 316, 317, 318, 319, 320, 321, 322, 323, 325, 326, 327, 328, 329, 330, 339, 346, 347, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 384, 385, 386, 387, 388, 389, 390, 391, 392, 394, 395, 396, 397, 398, 399, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424, 425, 426, 427, 429, 430, 431, 432, 433, 434, 435, 436, 437, 438, 439, 440, 441, 442, 443, 444, 445, 446, 447, 449, 450, 451, 452, 453, 454, 455, 456, 457, 459, 460, 461, 462, 463, 464, 465, 466, 467, 468, 469, 470, 472, 473, 474, 475, 476, 477, 479, 480, 481, 482,
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('policy_templates', function (Blueprint $table) {
            $table->boolean('include_in_benefits_summary')->default(0);
        });

        Schema::table('policies', function (Blueprint $table) {
            $table->softDeletes();
            $table->boolean('include_in_benefits_summary')->default(0);
            $table->string('delete_reason', 255)->nullable();
        });

        PolicyTemplate::whereIn('id', $this->policy_templates)
            ->update([
                'include_in_benefits_summary' => 1,
            ]);

        Policy::whereIn('template_id', $this->policy_templates)
            ->update([
                'include_in_benefits_summary' => 1,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('policies', 'include_in_benefits_summary')) {
            Schema::table('policies', function (Blueprint $table) {
                $table->dropColumn('include_in_benefits_summary');
            });
        }

        if (Schema::hasColumn('policy_templates', 'include_in_benefits_summary')) {
            Schema::table('policies', function (Blueprint $table) {
                $table->dropColumn('include_in_benefits_summary');
            });
        }
    }
}
