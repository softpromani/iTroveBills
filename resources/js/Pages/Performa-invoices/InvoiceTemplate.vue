<template>
    <Head title="Customer Bill" />
    <button @click="printInvoice" class="m-3 btn btn-outline-dark buttondata">
        <i class="fa fa-print" aria-hidden="true"></i>
    </button>
    <button @click="exportToPDF" class="m-3 btn btn-outline-dark buttondata">
        <i class="fa fa-download" aria-hidden="true"></i>
    </button>
    <div class="container-fluid downloadbill">
        <div class="row text-center border border-black border-solid"><center>Proforma Invoice</center></div>
        <div class="row">
            <div class="border-t border-b border-l border-black border-solid col-2">
                <img class="img" :src="`/${props.invoice.company.logo}`" alt="Logo" />

            </div>
            <div class="border-t border-b border-r border-black border-solid col-10">
                <h1 class="text-center text-7xl font">
                    {{ props.invoice.company.company_name ?? "" }}
                </h1>
                <p class="text-center">
                    {{ props.invoice.company.address ?? "" }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="border-b border-l border-black border-solid col-4">
                <p class="text-center">
                    GSTIN- {{ props.invoice.company.gstin ?? "" }}
                </p>
            </div>
            <div class="border-b border-l border-black border-solid col-4">
                <p class="text-center">
                    LUT NO- {{ props.invoice?.lut?.lut_no ?? "" }} / {{ props.invoice?.lut?.formatted_expiry_date??'' }}
                </p>
            </div>
            <div class="border-b border-l border-r border-black border-solid col-4">
                <p class="text-center">
                    IEC-{{ props.invoice.company.iec ?? "" }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="border-b border-l border-r border-black border-solid col-12">
                <p class="text-center">
                    MOBILE-{{
                        props.invoice.company.mobile ?? ""
                    }}
                    &nbsp;&nbsp;&nbsp; EMAIL-{{
                        props.invoice.company.email ?? ""
                    }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-3">
                Invoice No:
            </div>
            <div class="border-b border-l border-black border-solid col-3">
                {{ props.invoice.invoice_number ?? "" }}
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-3">
                DATE :
            </div>
            <div class="border-b border-l border-r border-black border-solid col-3">
                {{ props.invoice.invoice_date ?? "" }}
            </div>
        </div>
        <div class="row">
            <div class="border-b border-l border-r border-black border-solid col-12">
                Details of reciever(Billed to):
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-2">
                NAME :
            </div>
            <div class="border-b border-l border-black border-solid col-4">
                {{ props.invoice.customer.company_name ?? "" }}
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-2">
                MOBILE NO. :
            </div>
            <div class="border-b border-l border-r border-black border-solid col-4">
                {{ props.invoice.customer.mobile ?? "" }}
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-2">
                ADDRESS :
            </div>

            <div class="border-b border-l border-r border-black border-solid col-10">
                {{ props.invoice.customer.address ?? "No address" }}
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-3">
                ID/TPIN/TIN NO
            </div>
            <div class="border-b border-l border-black border-solid col-3">
                {{ props.invoice.customer.gstin ?? "No ID/TPIN/TIN NO" }}
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-3">
                VEHICLE NO
            </div>
            <div class="border-b border-l border-r border-black border-solid col-3">
                {{ props.invoice.vehicle_no ?? "" }}
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-1">
                S.No
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-2">
                Description of Goods
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-2">
                HSN CODE
            </div>
            <div class="font-bold border-b border-l border-r border-black border-solid col-1">
                Qty
            </div>
            <div class="font-bold border-b border-black border-solid col-1">
                Unit
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-1">
                Weight
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-2">
                Rate
            </div>
            <div class="font-bold border-b border-l border-r border-black border-solid col-2">
                Taxable Value
            </div>
        </div>
        <div class="row" v-for="(data, index) in props.invoice.invoiceitems" :key="data.id">
            <div class="border-b border-l border-black border-solid col-1">
                {{ index + 1 ?? "" }}
            </div>
            <div class="border-b border-l border-black border-solid col-2">
                {{ data.desc_product ?? "" }}
            </div>
            <div class="border-b border-l border-black border-solid col-2">
                {{ data.hsn_code ?? "" }}
            </div>
            <div class="border-b border-l border-r border-black border-solid col-1">
                {{ data.quantity ?? "" }}
            </div>
            <div class="border-b border-black border-solid col-1">
                {{ data.unit ?? "" }}
            </div>
            <div class="border-b border-l border-black border-solid col-1">
                {{ data.weight ?? "" }}
            </div>
            <div class="border-b border-l border-black border-solid col-2">
                {{ data.rate ?? "" }}
            </div>
            <div class="border-b border-l border-r border-black border-solid col-2">
                {{ (data.rate * data.quantity ?? 0).toFixed(2) }}
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-3">
                No. OF PACKAGES
            </div>
            <div class="border-b border-l border-black border-solid col-1">
                {{ props.invoice.no_packets ?? "No Packs" }}
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-3">
                Total weight
            </div>
            <div class="border-b border-l border-black border-solid col-1">
                {{ props.invoice.total_weight ?? "" }}
            </div>
            <div class="font-bold border-b border-l border-black border-solid col-2">
                Sub Total
            </div>
            <div class="border-b border-l border-r border-black border-solid col-2">
                ₹ {{ props.invoice.total_ammount ?? "" }}
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-4">
                Amount in words
            </div>
            <div class="border-b border-l border-r border-black border-solid col-8">
                {{ amountInWords }}
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-6">
                OUR BANK DETAILS
            </div>
            <div class="font-bold border-b border-l border-r border-black border-solid col-6">
                {{ props.invoice.company.company_name ?? "" }}
            </div>
        </div>
        <div class="row">
            <div class="font-bold border-b border-l border-black border-solid col-6">
                <p class="pt-3">
                    Name of bank - {{ props.invoice.company.bank_name ?? "" }}
                </p>
                <p class="-pt-3">
                    IFSC-{{ props.invoice.company.bank_ifsc ?? "" }}
                </p>
                <p class="-pt-3">
                    Account Number -
                    {{ props.invoice.company.bank_account_no ?? "" }}
                </p>
                <p class="-pt-3">
                    A D CODE-{{ props.invoice.company.ad_code ?? "" }}
                </p>
            </div>
            <div class="font-bold border-b border-l border-r border-black border-solid col-3">
                <img class="img" :src="`/${props.invoice.company.sign ?? '' }`" alt="Sign" style="height:80px;width:230px" />
            </div>
              <div class="font-bold border-b border-l border-r border-black border-solid col-3">
                <img class="img" :src="`/${props.invoice.company.bank_qr ?? ''}`" alt="QR" style="height:80px;width:230px" />
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3 col-lg-2" v-for="(image,index) in props.invoice?.company?.brand_banner" :key="index">
                <div class="brand-image-container">
                    <img :src="`/${image}`" alt="Brand Banner" class="img-fluid" style="height:80px;width:230px" />
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
const printInvoice = () => {
    window.print();
};
const props = defineProps({
    invoice: Object,
});


const amountInWords = ref("");

const rupeeWords = [
    "",
    "One",
    "Two",
    "Three",
    "Four",
    "Five",
    "Six",
    "Seven",
    "Eight",
    "Nine",
];
const tensWords = [
    "",
    "Ten",
    "Twenty",
    "Thirty",
    "Forty",
    "Fifty",
    "Sixty",
    "Seventy",
    "Eighty",
    "Ninety",
];
const teensWords = [
    "Ten",
    "Eleven",
    "Twelve",
    "Thirteen",
    "Fourteen",
    "Fifteen",
    "Sixteen",
    "Seventeen",
    "Eighteen",
    "Nineteen",
];

const convertToWordsRecursive = (amount) => {
    console.log(amount);
    if(amount<1){
        return "";
    }
    if (amount < 10) {
        return rupeeWords[amount];
    } else if (amount < 20) {
        return teensWords[amount - 10];
    } else if (amount < 100) {
        return (
            tensWords[Math.floor(amount / 10)] + " " + rupeeWords[amount % 10]
        );
    } else if (amount < 1000) {
        return (
            rupeeWords[Math.floor(amount / 100)] +
            " Hundred " +
            convertToWordsRecursive(amount % 100)
        );
    } else if (amount < 100000) {
        return (
            convertToWordsRecursive(Math.floor(amount / 1000)) +
            " Thousand " +
            convertToWordsRecursive(amount % 1000)
        );
    } else if (amount < 10000000) {
        return (
            convertToWordsRecursive(Math.floor(amount / 100000)) +
            " Lakh " +
            convertToWordsRecursive(amount % 100000)
        );
    } else {
        return (
            convertToWordsRecursive(Math.floor(amount / 10000000)) +
            " Crore " +
            convertToWordsRecursive(amount % 10000000)
        );
    }
};

const convertToWords = () => {
    const amountValue = Math.floor(props.invoice.total_ammount);
    const decimalPart = Math.round(
        (props.invoice.total_ammount - amountValue) * 100
    );

    // Converting the main part of the amount to words
    const amountValueWords = convertToWordsRecursive(amountValue);

    // Check if there is any decimal part to convert
    if (decimalPart > 0) {
        const decimalPartWords = convertToWordsRecursive(decimalPart);
        amountInWords.value = `${amountValueWords} Rupees and ${decimalPartWords} Paise`;
    } else {
        // If there is no decimal part, exclude "and [decimalPartWords] Paise"
        amountInWords.value = `${amountValueWords} Rupees`;
    }
};

onMounted(() => {
    convertToWords();
});

</script>
<style>
    .font {
        font-size: 42px;
        font-weight: bolder;
        font-family: "Algerian";
        letter-spacing: 5px;
        margin-top: 10px;
    }

    .height {
        height: 80px;
        overflow: hidden;
    }

    .images {
        height: auto;
        width: 100%;
        object-fit: cover;
        object-position: center;
        padding-top: 10px;
    }

    .img {
        width: 100px;
        padding: 5px;
    }

    @media print {
        .buttondata {
            visibility: hidden;
        }
    }
</style>
