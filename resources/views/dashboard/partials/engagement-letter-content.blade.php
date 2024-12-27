<div id="engagement-letter" style="font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: .625rem;">
        <img src="{{ asset('assets/images/redtax-logo.png') }}" width="160" height="auto" alt="RED Tax Financial Services Logo" style="display: block; margin: 0 auto;">
    </div>

    <h2 style="text-align: center; margin-bottom:1.625rem;">CLIENT ENGAGEMENT LETTER FOR {{ date('Y') }} TAX PREPARATION SERVICES</h2>
    
    <p>Dear {{ auth()->user()->name }}</p>

    <p>RED Tax Services appreciates the opportunity to prepare your Personal/Business Income Tax Returns.</p>

    <p>This letter sets forth the services RED Tax Services provides as part of the tax preparation process, states potential conflicts of interest, and outlines your responsibilities as a client.</p>

    <p>The return(s) will be prepared based on information and documentation you provide without independent verification by RED Tax Services. RED Tax Services will provide you with a questionnaire to assist you in gathering and organizing the required tax return data in order to keep the tax preparation fees to a minimum. You will make available information about all of your income and deductions so that substantially correct amounts of income and tax can be properly reported. It is your responsibility to maintain your records, the documentation necessary to support the data used in preparing your tax returns.  RED Tax Services is not responsible for the disallowance of doubtful deductions or inadequately supported documentation, nor for resulting taxes, penalties and interest.</p> 

    <p>You are expected to promptly provide requested follow-up materials and any missing information. If RED Tax Services has not received all of your tax return information in early April, we may not be able to complete the return before the filing due date. If your returns are not filed by midnight of April 17, 2023, you may be subject to late filing and/or late payment penalties.</p>

    <p>RED Tax Services is responsible for preparing only the returns listed above. The preparation fee does not include responding to inquiries or examinations by taxing authorities. However, RED Tax Services is available to represent you and the fees for such services are at the RED Tax Services’ standard rates and would be covered under a separate engagement letter.</p>

    <p>It is understood that anything you tell RED Tax Services during the interview for the preparation of your tax return is confidential, but not protected from the IRS or state tax authority. In addition, RED Tax Services cannot disregard the implications of any information you provide in the process of preparing your return. Any of the work papers used to prepare your returns, as well as the communications between you and RED Tax Services can be summoned by the IRS in a legal action against you. If this is of concern to you, you should discuss this with legal counsel prior to engaging RED Tax Services for the preparation of your returns.</p>

    <p>RED Tax Services will use its best judgment to resolve questions in your favor where a tax law is unclear, if there is a reasonable justification for doing so. Whenever we are aware that a possibly applicable tax law is unclear or that there are conflicting interpretations of the law by authorities (e.g., tax agencies and courts), we will explain the possible positions that may be taken on your return. We will follow whatever position you request, so long as it is consistent with the codes and regulations and interpretations that have been promulgated. If the IRS or a state tax agency should later contest the position taken, there may be an assessment of additional tax plus interest and penalties. We assume no liability for any such additional penalties or assessments.</p>
    
    <ul>
        <li>
            <i class="bi bi-dot fs-4"></i> If you were married on 12/31/2022, you have a choice of filing a joint or separate tax return for 2022; if there are dependents, one of you may qualify for Head of Household, while the other must file as Married Filing Separately.
        </li>
        <li>
            <i class="bi bi-dot fs-4"></i> If you file joint returns, you are accepting joint and/or separate responsibility for any tax assessed on the returns. Be especially concerned if there is an unpaid liability on the final returns as submitted; you can be held separately liable for the full amount of the underpayment. If you have any questions about your potential liability, please ask.
        </li>
        <li>
            <i class="bi bi-dot fs-4"></i>  If jointly filed returns (from any year) are later challenged by the IRS or state tax agency and any additional tax is assessed, each filer can be held liable for the full additional tax. If you are separated or contemplating divorce, you may wish to make sure any dissolution agreement reflects that any additional tax for the year will be paid by the individual who generated the additional income. However, this will not prevent the IRS or state agency, if applicable, from assessing the tax or attempting to collect it from both parties.
        </li>
        <li>
            <i class="bi bi-dot fs-4"></i> If joint returns are prepared for you that are later challenged by the IRS or state tax agency, RED Tax Services will not be allowed to represent either of you separately and will only be able to represent both of you if the representation can be provided objectively and with written consent from both of you.
        </li>
        <li>
            <i class="bi bi-dot fs-4"></i> If you are contemplating dissolution of marriage or were previously married to another client of RED Tax Services, you must understand that preparing the returns of both can involve inherent conflicts of interest for the person being asked to prepare the returns. Therefore, before RED Tax Services can prepare your return, you acknowledge that RED Tax Services cannot place information on your return in conflict with the information used in preparing your spouse’s or former spouse’s return. Additionally, if RED Tax Services represents both parties, conversations or other communications by either party with RED Tax Services are not considered confidential and are available to the other party. In fact, RED Tax Services may be required to disclose any oral or written communications between RED Tax Services and one party to the other party.
        </li>
    </ul>

    <p>Fees for services will be at the RED Tax Services’ standard rates plus out-of-pocket expenses. In some circumstances, a retainer may be required. Payment for service is due when rendered and, in some circumstances, interim billings may be submitted as work progresses and expenses are incurred.</p>

    <p>You will be provided with copies of the completed returns. It will be your responsibility to review the documents carefully before signing and filing the returns or signing the authorization for RED Tax Services to electronically file the returns (if RED Tax Services chooses to offer electronic filing services) to verify that the information is correct and accurate.</p>
    
    @if(auth()->user()->agreed_to_terms === 1)
        <p>I, {{ auth()->user()->name }}, has agreed to the terms and conditions outlined in this document.</p>
    @endif
    
    @if(auth()->user()->agreed_on)
        <h3 style="font-weight:medium; margin-top:2rem; text-align:right;">{{ strtoupper(auth()->user()->name) }} - {{ date('m/d/Y', strtotime(auth()->user()->agreed_on)) }}</h3>
    @endif
</div> 