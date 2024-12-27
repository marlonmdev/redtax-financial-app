<?php

namespace App\Enums;

enum AppointmentType: string
{
    case INDIVIDUAL_TAX_PREPARATION_SF = 'Individual Tax Preparation > Short Form';
    case INDIVIDUAL_TAX_PREPARATION_LF = 'Individual Tax Preparation > Long Form';
    case GROUP_TAX_PREPARATION = 'Group Tax Preparation';
    case BUSINESS_TAX_PREPARATION = 'Business Tax Preparation';
    case LIFE_INSURANCE_IN_OFFICE = 'Life Insurance > In-Office';
    case LIFE_INSURANCE_ZOOM = 'Life Insurance > Zoom';
    case BUSINESS_CONSULTING = 'Business Consulting';
}
