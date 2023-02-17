<?php

namespace Bentericksen\Settings;

class Industries
{

    public function getIndustries()
    {
        return [
            'dental' => [
                'title' => 'Dental',
                'subtype' => [
                    'general' => 'General',
                    'endodontic' => 'Endodontic',
                    'cosmetic' => 'Cosmetic',
                    'financial_services' => 'Financial Services',
                    'lab' => 'Lab',
                    'oral_maxillofacial' => 'Oral Maxillofacial',
                    'oral_surgery' => 'Oral Surgery',
                    'orthodontic' => 'Orthodontic',
                    'pediatric' => 'Pediatric',
                    'periodontic' => 'Periodontic',
                    'pedodontic' => 'Pedodontic',
                    'prosthodontics' => 'Prosthodontics',
                ],
            ],
            'medical' => [
                'title' => 'Medical',
                'subtype' => [
                    'chiropractic' => 'Chiropractic',
                    'cosmetic_surgery' => 'Cosmetic Surgery',
                    'internal_medicine' => 'Internal Medicine',
                    'optometry' => 'Optometry',
                    'pediatric' => 'Pediatric',
                    'surgical' => 'Surgical',
                ],
            ],
            'veterinarian' => [
                'title' => 'Veterinarian',
                'subtype' => [],
            ],
            'commercial' => [
                'title' => 'Commercial',
                'subtype' => [
                    'cpa' => 'CPA',
                    'mechanic' => 'Mechanic',
                    'restaurant' => 'Restaurant',
                    'retail' => 'Retail',
                ],
            ],
        ];
    }

    public function getMainIndustries()
    {
      $keys = array_keys($this->getIndustries());
      array_unshift($keys, 'ALL');

      return array_reduce($keys, function($out, $key) {
        $out[$key] = ucfirst($key);
        return $out;
      }, []);
    }
}
