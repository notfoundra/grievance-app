<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class CaseTypeDepartmentMap extends BaseConfig
{
    /**
     * Kategori saran (master_case_types.name) => Departemen penanggung jawab
     * (master_departments.name). Dipakai untuk auto-assign department_id
     * saat submission datang dari Formulir Publik (QR Code), karena pelapor
     * anonim tidak diharapkan tahu struktur departemen internal perusahaan.
     */
    public array $map = [
        'Occupational Health, Safety & Environment'          => 'HSE & Compliance',
        'Wages & Incentives'                                  => 'HRD',
        'Benefits'                                            => 'HRD',
        'General Facilities'                                  => 'GA',
        'Harassment & Abuse'                                  => 'HRD',
        'Working Hours'                                       => 'HRD',
        'Production'                                          => 'Production',
        'Recruitment & Contract'                              => 'HRD',
        'Termination & Resignation'                           => 'HRD',
        'Personal Change & Performance Appraisal'             => 'HRD',
        'Disciplinary Action'                                 => 'HRD',
        'Workplace Disputes'                                  => 'HRD',
        'Communication & Grievance Channels'                  => 'HRD',
        'Freedom of association and workers representation'  => 'Union/Worker Representative',
        'Personal Affairs'                                    => 'HRD',
        'Others'                                               => 'Others',
    ];

    public string $defaultDepartment = 'Others';
}
