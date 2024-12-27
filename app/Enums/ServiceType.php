<?php

namespace App\Enums;

enum ServiceType: string
{
    case TAX_SERVICES = 'Tax Services';
    case BUSINESS_CONSULTING = 'Business Consulting';
    case TAX_PLANNING = 'Tax Planning';
    case HEALTH_INSURANCE = 'Health Insurance';
    case AUDIT_REPRESENTATION = 'Audit Representation';
    case BOOKKEEPING_AND_PAYROLL = 'Bookkeeping and Payroll';
    case LIFE_INSURANCE = 'Life Insurance';
}
