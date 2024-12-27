<?php

namespace App\Enums;

enum StatusType: string
{
    case NOT_STARTED = "Not Started";
    case PENDING = "Pending";
    case PENDING_E_FILE = "Pending > E-File";
    case PENDING_WAITING_FOR_DOCUMENT = "Pending > Waiting for Document";
    case PENDING_MISSING_PAYMENT = "Pending > Missing Payment";
    case IN_PROGRESS = "In Progress";
    case COMPLETED = "Completed";
    case INPUT_COMPLETED = "Input Completed";
    case WAITING_FOR_CLIENT_TO_UPLOAD_DOCUMENTS = "Waiting for Client to Upload Documents";
    case CLIENT_FILE_DOWNLOADED_BY_STAFF = "Client File Downloaded by Staff";
    case CLIENT_WILL_BRING_THE_DOCUMENTS = "Client Will Bring the Documents";
    case ON_HOLD_WAITING_FOR_PAYMENT = "On Hold > Waiting for Payment";
    case ON_HOLD_EXTENSION = "On Hold > Extension";
    case PACKAGING = "Packaging";
    case PACKAGING_COMPLETED_CLIENT_FOR_SIGNATURE = "Packaging > Completed > Client for Signature";
    case PACKAGING_COMPLETED_RECEIVED_CLIENT_SIGNATURE = "Packaging > Completed > Received Client's Signature";
    case PACKAGING_COMPLETED_EMAILED_CLIENT_FOR_SIGNATURE = "Packaging > Completed > Emailed Client for Signature";
    case PACKAGING_COMPLETED_RECEIVED_EMAIL_CLIENT_SIGNATURE = "Packaging > Completed > Received Email Client's Signature";
    case BACKUP_CLIENT_FILE_TO_SERVER = "Backup Client File to Server";
}
