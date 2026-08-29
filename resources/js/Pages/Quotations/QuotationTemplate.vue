<template>
    <Head :title="`Quotation - ${quotation.quotation_number}`" />

    <!-- Action Bar (Hidden on Print) -->
    <div class="no-print p-3 bg-light border-bottom sticky-top d-flex align-items-center justify-content-between shadow-sm">
        <div class="d-flex align-items-center">
            <Link :href="route('quotation.list')" class="btn btn-outline-secondary me-2">
                <i class="fa fa-arrow-left me-1"></i> Back to Quotations
            </Link>
            <span class="badge bg-purple text-white px-3 py-2 fs-6">
                Ref: {{ quotation.quotation_number }}
            </span>
        </div>
        <div class="d-flex align-items-center">
            <Link :href="route('quotation.edit', quotation.id)" class="btn btn-outline-primary me-2">
                <i class="fa fa-edit me-1"></i> Edit
            </Link>
            <button @click="printQuotation" class="btn btn-dark me-2">
                <i class="fa fa-print me-1"></i> Print Quotation
            </button>
            <button @click="exportToPDF" class="btn btn-purple text-white me-2">
                <i class="fa fa-download me-1"></i> Download PDF
            </button>
        </div>
    </div>

    <!-- Quotation Document Container -->
    <div class="quotation-print-wrapper py-4 px-2 bg-gray-100 min-vh-100" id="quotation-document">
        
        <!-- ========================================== -->
        <!-- PAGE 1: TITLE / COVER PAGE                 -->
        <!-- ========================================== -->
        <div class="quotation-page a4-page shadow-sm mx-auto bg-white position-relative d-flex flex-column justify-content-between">
            <!-- Header -->
            <div class="page-header-block">
                <div class="header-top-accent-line"></div>
                <div class="header-corporate-card d-flex align-items-center justify-content-between">
                    <div class="header-brand-left">
                        <img :src="companyLogoSrc" class="company-logo-img" alt="Logo" @error="onLogoError" />
                    </div>
                    <div class="header-info-center text-center flex-grow-1 px-3">
                        <h2 class="company-title-main">{{ companyName }}</h2>
                        <div class="company-sub-info">
                            <div class="mb-1"><strong>Address :-</strong> {{ companyAddress }}</div>
                            <div class="mb-1"><strong>Phone :-</strong> {{ companyPhone }}</div>
                            <div><strong>E-mail :-</strong> <span class="email-link">{{ primaryEmail }}</span> , <span class="email-link">{{ secondaryEmail }}</span></div>
                        </div>
                    </div>
                    <div class="header-badge-right text-end">
                        <div class="doc-badge">
                            <span class="doc-badge-title">COMMERCIAL PROPOSAL</span>
                            <span class="doc-badge-ref">Ref: {{ quotation.quotation_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="header-bottom-accent-line"></div>
            </div>

            <!-- Page 1 Center Body with Watermark -->
            <div class="page1-center-content my-auto position-relative text-center py-4">
                <!-- Authentic Logo Watermark in Center -->
                <img src="/itimages/it.png" class="cover-watermark-img" alt="Watermark" />

                <div class="position-relative z-index-2 cover-text-group">
                    <h1 class="quotation-for-heading mb-4">QUOTATION FOR</h1>
                    
                    <h2 class="quotation-main-subject mb-5">
                        {{ quotation.title }}
                    </h2>

                    <div class="client-cover-box my-4">
                        <h4 class="client-name-title mb-1">
                            Business Name – <span class="client-highlight">{{ customerName }}</span>
                        </h4>
                        <p class="client-address-text mb-0">
                            Address – {{ customerAddress }}
                        </p>
                    </div>

                    <div class="by-divider my-4">
                        <span class="by-text">BY</span>
                    </div>

                    <div class="issuer-cover-box">
                        <div class="d-inline-block issuer-underline-wrap mb-1">
                            <h3 class="issuer-company-name mb-0">{{ companyName }}</h3>
                            <div class="issuer-gold-line"></div>
                        </div>
                        <p class="issuer-address mb-1">{{ companyAddress }}</p>
                        <p class="issuer-website mb-0">
                            Website Link - <a :href="companyWebsiteUrl" target="_blank" class="website-link">{{ companyWebsite }}</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Global Footer Banner -->
            <div class="page-footer-banner text-white">
                <div class="row align-items-center text-center g-0">
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-user footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryPhone }}</div>
                                <div v-if="secondaryPhone">{{ secondaryPhone }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-envelope footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryEmail }}</div>
                                <div v-if="secondaryEmail">{{ secondaryEmail }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-map-marker-alt footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <span>{{ companyAddressShort }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PAGE 2: FORMAL PROPOSAL & COVER LETTER     -->
        <!-- ========================================== -->
        <div class="quotation-page a4-page shadow-sm mx-auto bg-white position-relative d-flex flex-column justify-content-between mt-4">
            <!-- Header -->
            <div class="page-header-block">
                <div class="header-top-accent-line"></div>
                <div class="header-corporate-card d-flex align-items-center justify-content-between">
                    <div class="header-brand-left">
                        <img :src="companyLogoSrc" class="company-logo-img" alt="Logo" @error="onLogoError" />
                    </div>
                    <div class="header-info-center text-center flex-grow-1 px-3">
                        <h2 class="company-title-main">{{ companyName }}</h2>
                        <div class="company-sub-info">
                            <div class="mb-1"><strong>Address :-</strong> {{ companyAddress }}</div>
                            <div class="mb-1"><strong>Phone :-</strong> {{ companyPhone }}</div>
                            <div><strong>E-mail :-</strong> <span class="email-link">{{ primaryEmail }}</span> , <span class="email-link">{{ secondaryEmail }}</span></div>
                        </div>
                    </div>
                    <div class="header-badge-right text-end">
                        <div class="doc-badge">
                            <span class="doc-badge-title">COMMERCIAL PROPOSAL</span>
                            <span class="doc-badge-ref">Ref: {{ quotation.quotation_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="header-bottom-accent-line"></div>
            </div>

            <!-- Page 2 Body Content -->
            <div class="page2-body-content flex-grow-1 pt-3 px-2">
                <!-- Reference No & Date -->
                <div class="d-flex justify-content-between align-items-center mb-3 letter-ref-bar">
                    <div class="fw-bold">
                        Reference No. - <span class="fw-normal">{{ quotation.quotation_number }}</span>
                    </div>
                    <div class="fw-bold">
                        Date – <span class="fw-normal">{{ formatDate(quotation.quotation_date) }}</span>
                    </div>
                </div>

                <!-- Recipient Address Block -->
                <div class="recipient-block mb-3">
                    <div class="fw-bold">To,</div>
                    <div class="recipient-lines ps-3">
                        <div class="fw-bold">{{ quotation.recipient_designation || 'The Registrar' }}</div>
                        <div class="fw-bold">{{ customerName }}</div>
                        <div>{{ customerAddress }}</div>
                    </div>
                </div>

                <!-- Subject Line -->
                <div class="subject-line mb-3">
                    <strong>Subject :</strong> <span class="fw-bold">{{ quotation.subject || quotation.title }}</span>
                </div>

                <div class="salutation-line mb-2">
                    <strong>Dear sir,</strong>
                </div>

                <!-- Letter Paragraphs -->
                <div class="letter-paragraphs-content">
                    <template v-if="Array.isArray(quotation.cover_letter_content) && quotation.cover_letter_content.length">
                        <p v-for="(p, idx) in quotation.cover_letter_content" :key="idx" class="letter-p mb-2 text-justify">
                            {{ p }}
                        </p>
                    </template>
                    <template v-else-if="typeof quotation.cover_letter_content === 'string' && quotation.cover_letter_content">
                        <p class="letter-p mb-2 text-justify" style="white-space: pre-line;">
                            {{ quotation.cover_letter_content }}
                        </p>
                    </template>
                    <template v-else>
                        <p class="letter-p mb-2 text-justify">
                            As per our discussions, kindly get our best proposal in regard to subject matter. We thank you very much for your keen interest shown towards our products & services.
                        </p>
                        <p class="letter-p mb-2 text-justify">
                            {{ companyName }} is a Professional Web Design & Development, Consultancy & Software Solutions Company remotely based in Lucknow, U.P. India and its Head office is located in Daragaon, Jaigaon, West Bengal.
                        </p>
                        <p class="letter-p mb-2 text-justify">
                            We offer industry-specific solutions and integration services through a unique onsite, offsite, offshore delivery model that helps our clients achieve reduced "time to market" for their products and world-class quality on-time and that too in budget. To help our clients to meet their goals by offering better and time-oriented services. We offer high-end creative solutions for business communication.
                        </p>
                        <p class="letter-p mb-2 text-justify">
                            {{ companyName }} Best Website Design & Development Company, We provide Custom Software Development, Accounting & Billing with GST, School Management Software, Hospital Management Software, Petrol Pump Software, CRM, HRM, ERP Real Estate software, Social Media Marketing (SMM), Search engine Optimization (SEO), Social Media Optimization (SMO) etc.
                        </p>
                        <p class="letter-p mb-2 text-justify">
                            In the light of above, we request you to kindly look in to the offer attached here with and revert back to us with favor for the assignment to make your project successful.
                        </p>
                        <p class="letter-p mb-2">
                            We assure you of our best services for all times.
                        </p>
                    </template>
                </div>
            </div>

            <!-- Global Footer Banner -->
            <div class="page-footer-banner text-white">
                <div class="row align-items-center text-center g-0">
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-user footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryPhone }}</div>
                                <div v-if="secondaryPhone">{{ secondaryPhone }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-envelope footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryEmail }}</div>
                                <div v-if="secondaryEmail">{{ secondaryEmail }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-map-marker-alt footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <span>{{ companyAddressShort }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PAGE 3: COMMERCIALS & SCOPE OF WORK TABLE  -->
        <!-- ========================================== -->
        <div class="quotation-page a4-page shadow-sm mx-auto bg-white position-relative d-flex flex-column justify-content-between mt-4">
            <!-- Header -->
            <div class="page-header-block">
                <div class="header-top-accent-line"></div>
                <div class="header-corporate-card d-flex align-items-center justify-content-between">
                    <div class="header-brand-left">
                        <img :src="companyLogoSrc" class="company-logo-img" alt="Logo" @error="onLogoError" />
                    </div>
                    <div class="header-info-center text-center flex-grow-1 px-3">
                        <h2 class="company-title-main">{{ companyName }}</h2>
                        <div class="company-sub-info">
                            <div class="mb-1"><strong>Address :-</strong> {{ companyAddress }}</div>
                            <div class="mb-1"><strong>Phone :-</strong> {{ companyPhone }}</div>
                            <div><strong>E-mail :-</strong> <span class="email-link">{{ primaryEmail }}</span> , <span class="email-link">{{ secondaryEmail }}</span></div>
                        </div>
                    </div>
                    <div class="header-badge-right text-end">
                        <div class="doc-badge">
                            <span class="doc-badge-title">COMMERCIAL PROPOSAL</span>
                            <span class="doc-badge-ref">Ref: {{ quotation.quotation_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="header-bottom-accent-line"></div>
            </div>

            <!-- Page 3 Body Content (Costing Table) -->
            <div class="page3-body-content flex-grow-1 pt-2 px-2">
                <h5 class="scope-heading-underlined mb-3 text-center">
                    <em>Quotation for {{ quotation.subject || quotation.title }}</em>
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered quotation-commercial-table mb-0">
                        <thead>
                            <tr class="table-purple-header text-center align-middle">
                                <th style="width: 7%;">Sr.No.</th>
                                <th style="width: 53%;">Description</th>
                                <th style="width: 14%;">Unit</th>
                                <th style="width: 13%;">Unit Rate<br>(In Rs.)</th>
                                <th style="width: 13%;">Total Rate<br>(In Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, idx) in quotation.items" :key="item.id || idx">
                                <td class="text-center fw-bold align-top pt-2">{{ idx + 1 }}.</td>
                                <td class="align-top pt-2">
                                    <div class="item-scope-title fw-bold mb-1">{{ item.title }}</div>
                                    <ul v-if="Array.isArray(item.description_points) && item.description_points.length" class="item-bullet-list mb-0 ps-3">
                                        <li v-for="(pt, pidx) in item.description_points" :key="pidx" class="item-bullet-point">
                                            {{ pt }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-center fw-bold align-middle">{{ item.unit || '-' }}</td>
                                <td class="text-center fw-bold align-middle">{{ formatCurrency(item.unit_rate) }}/-</td>
                                <td class="text-center fw-bold align-middle">{{ formatCurrency(item.total_rate) }}/-</td>
                            </tr>

                            <!-- Total Costing Row -->
                            <tr class="total-costing-row">
                                <td colspan="3" class="total-costing-label text-center py-2">
                                    Total Costing
                                </td>
                                <td colspan="2" class="total-costing-amount text-center py-2">
                                    <div class="amount-val">{{ formatCurrency(quotation.total_amount) }} /-</div>
                                    <div class="amount-words">
                                        ( {{ amountInWords }} Only )
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tax Note -->
                <div class="tax-note-box mt-3">
                    <p class="mb-0">
                        <strong>Note :</strong> {{ quotation.tax_note || 'The total costing + @18% GST will be considered as final amount paid.' }}
                    </p>
                </div>
            </div>

            <!-- Global Footer Banner -->
            <div class="page-footer-banner text-white">
                <div class="row align-items-center text-center g-0">
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-user footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryPhone }}</div>
                                <div v-if="secondaryPhone">{{ secondaryPhone }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-envelope footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryEmail }}</div>
                                <div v-if="secondaryEmail">{{ secondaryEmail }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-map-marker-alt footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <span>{{ companyAddressShort }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PAGE 4: PAYMENT TERMS & CONDITIONS         -->
        <!-- ========================================== -->
        <div class="quotation-page a4-page shadow-sm mx-auto bg-white position-relative d-flex flex-column justify-content-between mt-4">
            <!-- Header -->
            <div class="page-header-block">
                <div class="header-top-accent-line"></div>
                <div class="header-corporate-card d-flex align-items-center justify-content-between">
                    <div class="header-brand-left">
                        <img :src="companyLogoSrc" class="company-logo-img" alt="Logo" @error="onLogoError" />
                    </div>
                    <div class="header-info-center text-center flex-grow-1 px-3">
                        <h2 class="company-title-main">{{ companyName }}</h2>
                        <div class="company-sub-info">
                            <div class="mb-1"><strong>Address :-</strong> {{ companyAddress }}</div>
                            <div class="mb-1"><strong>Phone :-</strong> {{ companyPhone }}</div>
                            <div><strong>E-mail :-</strong> <span class="email-link">{{ primaryEmail }}</span> , <span class="email-link">{{ secondaryEmail }}</span></div>
                        </div>
                    </div>
                    <div class="header-badge-right text-end">
                        <div class="doc-badge">
                            <span class="doc-badge-title">COMMERCIAL PROPOSAL</span>
                            <span class="doc-badge-ref">Ref: {{ quotation.quotation_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="header-bottom-accent-line"></div>
            </div>

            <!-- Page 4 Body Content (Terms & Conditions) -->
            <div class="page4-body-content flex-grow-1 pt-3 px-2">
                <h5 class="terms-main-title fw-bold mb-4">
                    Payment Terms &amp; Terms &amp; Conditions -
                </h5>

                <div class="terms-list-container">
                    <template v-if="Array.isArray(quotation.terms_and_conditions) && quotation.terms_and_conditions.length">
                        <div v-for="(term, tidx) in quotation.terms_and_conditions" :key="tidx" class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong v-if="term.title">{{ term.title }}: </strong>
                                <span>{{ term.description || term }}</span>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Taxes:</strong> Goods and Services Tax (GST) will be charged extra as applicable per prevailing Government regulations.
                            </div>
                        </div>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Payment Terms:</strong> 100% full payment is due immediately upon successful completion and handover of the scope of work.
                            </div>
                        </div>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Project Commencement:</strong> Work execution will begin upon receipt of an official Purchase Order (PO) / Work Order (WO).
                            </div>
                        </div>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Warranty &amp; Support:</strong> Includes a 1-Year Support Warranty covering remote assistance and online troubleshooting. On-site physical deployment support is limited to one (01) initial visit during setup and configuration.
                            </div>
                        </div>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Site Readiness:</strong> The client must ensure that the existing Local Area Network (LAN) infrastructure and cabling within the lab are fully functional prior to the start of installation.
                            </div>
                        </div>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Network Prerequisites:</strong> All client workstations must have active network connectivity and be capable of successfully communicating (PING) with the central server before deployment begins.
                            </div>
                        </div>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Network Rectification Clause:</strong> If the existing LAN infrastructure is found non-operational or faulty during deployment, passive network repair and troubleshooting will attract an additional service charge of ₹60,000/- plus GST per computer lab.
                            </div>
                        </div>
                        <div class="term-item d-flex align-items-start mb-3">
                            <span class="term-arrow me-2">➢</span>
                            <div class="term-text">
                                <strong>Hardware &amp; Spares:</strong> Any network components or hardware consumables (e.g., Network Switches, Cat6 Cables, Patch Cords, Access Points, RJ45 Connectors, Casing/Conduits) required for network restoration are not included in the base scope and will be billed separately upon approval of a supplementary order.
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Global Footer Banner -->
            <div class="page-footer-banner text-white">
                <div class="row align-items-center text-center g-0">
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-user footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryPhone }}</div>
                                <div v-if="secondaryPhone">{{ secondaryPhone }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col border-end border-white border-opacity-25 py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-envelope footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <div>{{ primaryEmail }}</div>
                                <div v-if="secondaryEmail">{{ secondaryEmail }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 footer-col py-2 px-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="footer-icon-circle me-2">
                                <i class="fa fa-map-marker-alt footer-icon"></i>
                            </div>
                            <div class="footer-text-block text-start">
                                <span>{{ companyAddressShort }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    quotation: Object,
});

// Company Details Computed
const company = computed(() => props.quotation?.company || {});
const companyName = computed(() => company.value.company_name || 'INNOVATION TROVE L.L.P.');
const companyAddress = computed(() => {
    if (company.value.address) {
        return `${company.value.address}${company.value.city ? ', ' + company.value.city : ''}${company.value.pin ? ' - ' + company.value.pin : ''}`;
    }
    return 'Daragaon, Near Petrol Pump, Jaigaon, Jalpaiguri, West Bengal - 736182';
});
const companyAddressShort = computed(() => {
    if (company.value.address) {
        return `${company.value.address}, ${company.value.city || 'Jaigaon'} (${company.value.pin || '736182'})`;
    }
    return 'Aragaon, Near Petrol Pump, Jaigaon-West Bengal (736182)';
});
const companyPhone = computed(() => company.value.mobile || '9648471515, 9648391515, 9648061515');
const primaryPhone = computed(() => {
    const phones = companyPhone.value.split(',');
    return '+91 ' + (phones[0]?.trim() || '9648471515');
});
const secondaryPhone = computed(() => {
    const phones = companyPhone.value.split(',');
    return phones[1] ? '+91 ' + phones[1].trim() : '+91 9648061515';
});

const companyEmail = computed(() => company.value.email || 'hr.innovationtrove@gmail.com , info@innovationtrove.in');
const primaryEmail = computed(() => {
    const emails = companyEmail.value.split(',');
    return emails[0]?.trim() || 'hr.innovationtrove@gmail.com';
});
const secondaryEmail = computed(() => {
    const emails = companyEmail.value.split(',');
    return emails[1] ? emails[1].trim() : 'info@innovationtrove.in';
});

const companyLogoSrc = computed(() => {
    if (company.value.logo) {
        if (company.value.logo.startsWith('http') || company.value.logo.startsWith('/')) {
            return company.value.logo;
        }
        return `/${company.value.logo}`;
    }
    return '/itimages/itlogo.png';
});

const onLogoError = (e) => {
    e.target.src = '/itimages/itlogo.png';
};

const companyWebsite = computed(() => company.value.website || 'www.innovationtrove.in');
const companyWebsiteUrl = computed(() => {
    const site = companyWebsite.value;
    return site.startsWith('http') ? site : `https://${site}`;
});

// Customer Details Computed
const customerData = computed(() => {
    if (props.quotation?.customer_data) {
        return typeof props.quotation.customer_data === 'string' 
            ? JSON.parse(props.quotation.customer_data) 
            : props.quotation.customer_data;
    }
    if (props.quotation?.customer?.customer_detail) {
        return props.quotation.customer.customer_detail;
    }
    return {};
});

const customerName = computed(() => {
    return customerData.value.company_name || customerData.value.name || 'Lalit Narayan Mithila University';
});

const customerAddress = computed(() => {
    return customerData.value.address || 'Kameshwaranagar, Darbhanga, Bihar - 846008';
});

// Currency formatting
const formatCurrency = (val) => {
    if (!val && val !== 0) return '0';
    return Number(val).toLocaleString('en-IN');
};

// Date formatting
const formatDate = (val) => {
    if (!val) return '';
    const d = new Date(val);
    if (isNaN(d)) return val;
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
};

// Number to words converter
const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
const tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

const convertNumberToWords = (num) => {
    if (num < 1) return '';
    if (num < 10) return ones[num];
    if (num < 20) return teens[num - 10];
    if (num < 100) return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? ' ' + ones[num % 10] : '');
    if (num < 1000) return ones[Math.floor(num / 100)] + ' Hundred' + (num % 100 !== 0 ? ' ' + convertNumberToWords(num % 100) : '');
    if (num < 100000) return convertNumberToWords(Math.floor(num / 1000)) + ' Thousand' + (num % 1000 !== 0 ? ' ' + convertNumberToWords(num % 1000) : '');
    if (num < 10000000) return convertNumberToWords(Math.floor(num / 100000)) + ' Lakh' + (num % 100000 !== 0 ? ' ' + convertNumberToWords(num % 100000) : '');
    return convertNumberToWords(Math.floor(num / 10000000)) + ' Crore' + (num % 10000000 !== 0 ? ' ' + convertNumberToWords(num % 10000000) : '');
};

const amountInWords = computed(() => {
    const total = Math.floor(props.quotation?.total_amount || 0);
    if (!total) return 'Zero Rupees';
    return convertNumberToWords(total) + ' INR';
});

// Print & PDF
const printQuotation = () => {
    window.print();
};

const exportToPDF = () => {
    window.print();
};
</script>

<style scoped>
/* Page Layout */
.quotation-print-wrapper {
    font-family: 'Times New Roman', Times, Georgia, serif;
    color: #1a1a1a;
}

.a4-page {
    width: 210mm;
    min-height: 297mm;
    padding: 10mm 14mm 10mm 14mm;
    box-sizing: border-box;
    background: #ffffff;
    border: 1px solid #e0e0e0;
}

/* Header Corporate Design */
.page-header-block {
    width: 100%;
    margin-bottom: 8px;
}

.header-top-accent-line {
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #c03aa4 0%, #b233a0 25%, #9b2ea5 50%, #802894 100%);
    border-radius: 4px 4px 0 0;
}

.header-corporate-card {
    background: linear-gradient(135deg, #fdf7fc 0%, #ffffff 50%, #faf1fa 100%);
    border-left: 1px solid rgba(181, 27, 117, 0.15);
    border-right: 1px solid rgba(181, 27, 117, 0.15);
    padding: 10px 16px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
}

.header-brand-left {
    max-width: 24%;
    display: flex;
    align-items: center;
}

.company-logo-img {
    max-height: 68px;
    width: auto;
    max-width: 100%;
    object-fit: contain;
}

.company-title-main {
    font-size: 22px;
    font-weight: 900;
    color: #b51b75;
    letter-spacing: 0.8px;
    margin: 0 0 3px 0;
    text-transform: uppercase;
}

.company-sub-info {
    font-size: 13.5px;
    color: #1a1a1a;
    line-height: 1.35;
}

.email-link {
    text-decoration: underline;
    color: #1a1a1a;
}

.header-badge-right {
    max-width: 24%;
    display: flex;
    justify-content: flex-end;
}

.doc-badge {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-end;
    background: linear-gradient(135deg, #8a1762 0%, #6c2b87 100%);
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(108, 43, 135, 0.2);
}

.doc-badge-title {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.8px;
    color: #ffd6f4;
    text-transform: uppercase;
    white-space: nowrap;
}

.doc-badge-ref {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
    color: #ffffff;
    white-space: nowrap;
}

.header-bottom-accent-line {
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, #c03aa4 0%, #b233a0 25%, #9b2ea5 50%, #802894 100%);
    border-radius: 0 0 4px 4px;
}

/* Page 1 Cover Styles */
.page1-center-content {
    min-height: 175mm;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.cover-watermark-img {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 390px;
    max-width: 80%;
    height: auto;
    opacity: 0.16;
    pointer-events: none;
    z-index: 1;
}

.cover-text-group {
    width: 100%;
    position: relative;
    z-index: 2;
}

.quotation-for-heading {
    font-size: 30px;
    font-weight: 900;
    color: #7b1154;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.quotation-main-subject {
    font-size: 22px;
    font-weight: 800;
    color: #b51b75;
    max-width: 85%;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.4;
}

.client-name-title {
    font-size: 18px;
    color: #80145c;
    font-weight: 600;
}

.client-highlight {
    color: #80145c;
    font-weight: 800;
}

.client-address-text {
    font-size: 15px;
    color: #80145c;
}

.by-divider .by-text {
    font-size: 13px;
    font-weight: 800;
    color: #4a154b;
    letter-spacing: 2px;
}

.issuer-underline-wrap {
    position: relative;
}

.issuer-company-name {
    font-size: 23px;
    font-weight: 900;
    color: #8a1762;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}

.issuer-gold-line {
    width: 100%;
    height: 2px;
    background: #e08e2f;
    margin-top: 2px;
}

.issuer-address {
    font-size: 14.5px;
    font-weight: 600;
    color: #8a1762;
}

.issuer-website {
    font-size: 14px;
    font-weight: 600;
    color: #8a1762;
}

.website-link {
    color: #0b5ed7;
    text-decoration: underline;
}

/* Page 2 Cover Letter */
.letter-ref-bar {
    font-size: 14.5px;
}

.recipient-block {
    font-size: 15px;
    line-height: 1.4;
}

.subject-line {
    font-size: 15px;
}

.letter-p {
    font-size: 14px;
    line-height: 1.45;
    color: #111;
}

.text-justify {
    text-align: justify;
}

/* Page 3 Commercials Table */
.scope-heading-underlined {
    font-size: 15.5px;
    font-weight: 700;
    text-decoration: underline;
    color: #111;
}

.quotation-commercial-table {
    border: 1.5px solid #222;
    font-size: 14px;
}

.quotation-commercial-table th,
.quotation-commercial-table td {
    border: 1px solid #222;
}

.table-purple-header {
    background-color: #6c2b87 !important;
    color: #ffffff !important;
    font-size: 14px;
    font-weight: 700;
}

.table-purple-header th {
    background-color: #6c2b87 !important;
    color: #ffffff !important;
    border-color: #4a154b !important;
    padding: 8px 6px;
}

.item-scope-title {
    font-size: 14.5px;
    color: #111;
}

.item-bullet-list {
    font-size: 13px;
    line-height: 1.35;
    list-style-type: disc;
}

.item-bullet-point {
    margin-bottom: 4px;
}

.total-costing-row {
    background-color: #6c2b87 !important;
    color: #ffffff !important;
    font-weight: 700;
}

.total-costing-row td {
    background-color: #6c2b87 !important;
    color: #ffffff !important;
    border-color: #4a154b !important;
}

.total-costing-label {
    font-size: 19px;
    letter-spacing: 0.5px;
    vertical-align: middle;
}

.total-costing-amount .amount-val {
    font-size: 16px;
    font-weight: 800;
}

.total-costing-amount .amount-words {
    font-size: 11.5px;
    font-style: italic;
    font-weight: normal;
}

.tax-note-box {
    font-size: 13.5px;
    color: #111;
}

/* Page 4 Terms & Conditions */
.terms-main-title {
    font-size: 15.5px;
    color: #111;
}

.terms-list-container {
    font-size: 13.5px;
    line-height: 1.45;
}

.term-arrow {
    color: #111;
    font-size: 14px;
    line-height: 1.2;
}

/* Footer Banner */
.page-footer-banner {
    background: linear-gradient(90deg, #be38a1 0%, #b233a0 25%, #9b2ea5 50%, #802894 100%);
    border-radius: 4px;
    margin-top: 10px;
    width: 100%;
}

.footer-icon-circle {
    display: flex;
    align-items: center;
    justify-content: center;
}

.footer-icon {
    font-size: 17px;
    color: #ffffff;
}

.footer-text-block {
    font-size: 11px;
    font-family: Arial, Helvetica, sans-serif;
    line-height: 1.25;
    color: #ffffff;
    font-weight: 500;
}

.btn-purple {
    background-color: #6c2b87;
    border-color: #6c2b87;
}

.btn-purple:hover {
    background-color: #551d6c;
    border-color: #551d6c;
}

.bg-purple {
    background-color: #6c2b87 !important;
}

/* Print Specific Rules */
@media print {
    .no-print {
        display: none !important;
    }

    body, html {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .quotation-print-wrapper {
        padding: 0 !important;
        background: #ffffff !important;
    }

    .a4-page {
        width: 100% !important;
        min-height: 100vh !important;
        margin: 0 !important;
        padding: 8mm 10mm 8mm 10mm !important;
        border: none !important;
        box-shadow: none !important;
        page-break-before: always;
        page-break-after: always;
        page-break-inside: avoid;
    }

    .a4-page:first-child {
        page-break-before: avoid;
    }

    .page-footer-banner {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .table-purple-header,
    .table-purple-header th,
    .total-costing-row,
    .total-costing-row td {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background-color: #6c2b87 !important;
        color: #ffffff !important;
    }
}
</style>
