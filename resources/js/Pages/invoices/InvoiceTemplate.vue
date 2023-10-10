<template>
    <Head title="Customer view" />
    <button @click="printInvoice">Print Invoice</button>
    <div class="container-fluid">
        <div class="row">
            <div
                class="col-2 border-t border-b border-l border-solid border-black"
            >
                <img
                    class="img"
                    :src="props.invoice.company.logo ?? ''"
                    alt="jpeg"
                />
            </div>
            <div
                class="col-10 border-t border-b border-r border-solid border-black"
            >
                <h1 class="text-center text-7xl font">
                    {{ props.invoice.company.company_name ?? "" }}
                </h1>
                <p class="text-center">
                    {{ props.invoice.company.address ?? "" }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-6 border-solid border-b border-l border-black">
                <p class="text-center">
                    GSTIN- {{ props.invoice.company.gstin ?? "" }}
                </p>
            </div>
            <div
                class="col-6 border-solid border-l border-b border-r border-black"
            >
                <p class="text-center">
                    IEC-{{ props.invoice.company.iec ?? "" }}
                </p>
            </div>
        </div>
        <div class="row">
            <div
                class="col-12 border-b border-l border-r border-black border-solid"
            >
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
            <div
                class="col-3 border-b border-l font-bold border-black border-solid"
            >
                Invoice No:
            </div>
            <div class="col-3 border-b border-l border-black border-solid">
                {{ props.invoice.invoice_number ?? "" }}
            </div>
            <div
                class="col-3 border-b border-l font-bold border-black border-solid"
            >
                DATE :
            </div>
            <div
                class="col-3 border-b border-l border-r border-black border-solid"
            >
                {{ props.invoice.invoice_date ?? "" }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-12 border-b border-l border-r border-black border-solid"
            >
                Details of reciever(Billed to):
            </div>
        </div>
        <div class="row">
            <div
                class="col-2 border-b border-l font-bold border-black border-solid"
            >
                NAME :
            </div>
            <div class="col-4 border-b border-l border-black border-solid">
                {{ props.invoice.customer.name ?? "" }}
            </div>
            <div
                class="col-2 border-b border-l font-bold border-black border-solid"
            >
                MOBILE NO. :
            </div>
            <div
                class="col-4 border-b border-l border-r border-black border-solid"
            >
                {{ props.invoice.customer.mobile ?? "" }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-2 border-b border-l font-bold border-black border-solid"
            >
                ADDRESS :
            </div>

            <div
                class="col-10 border-b border-l border-r border-black border-solid"
            >
                {{ props.invoice.customer.address ?? "No address" }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-3 border-b font-bold border-l border-black border-solid"
            >
                ID/TPIN/TIN NO
            </div>
            <div class="col-3 border-b border-l border-black border-solid">
                {{ props.invoice.customer.gst ?? "No ID/TPIN/TIN NO" }}
            </div>
            <div
                class="col-3 border-b border-l font-bold border-black border-solid"
            >
                VEHICLE NO
            </div>
            <div
                class="col-3 border-b border-l border-r border-black border-solid"
            >
                {{ props.invoice.vehicle_no ?? "" }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-1 border-b border-l font-bold border-black border-solid"
            >
                S.No
            </div>
            <div
                class="col-2 border-b border-l font-bold border-black border-solid"
            >
                Description of Goods
            </div>
            <div
                class="col-2 border-b border-l font-bold border-black border-solid"
            >
                HSN CODE
            </div>
            <div
                class="col-2 border-b border-l font-bold border-r border-black border-solid"
            >
                Quantity
            </div>
            <div class="col-1 border-b font-bold border-black border-solid">
                Unit
            </div>
            <div
                class="col-1 border-b border-l font-bold border-black border-solid"
            >
                Weight
            </div>
            <div
                class="col-1 border-b border-l font-bold border-black border-solid"
            >
                Rate
            </div>
            <div
                class="col-2 border-b border-l border-r font-bold border-black border-solid"
            >
                Taxable Value
            </div>
        </div>
        <div
            class="row"
            v-for="(data, index) in props.invoice.invoiceitems"
            :key="data.id"
        >
            <div class="col-1 border-b border-l border-black border-solid">
                {{ index + 1 ?? "" }}
            </div>
            <div class="col-2 border-b border-l border-black border-solid">
                {{ data.desc_product ?? "" }}
            </div>
            <div class="col-2 border-b border-l border-black border-solid">
                {{ data.hsn_code ?? "" }}
            </div>
            <div
                class="col-2 border-b border-l border-r border-black border-solid"
            >
                {{ data.quantity ?? "" }}
            </div>
            <div class="col-1 border-b border-black border-solid">
                {{ data.unit ?? "" }}
            </div>
            <div class="col-1 border-b border-l border-black border-solid">
                {{ data.weight ?? "" }}
            </div>
            <div class="col-1 border-b border-l border-black border-solid">
                {{ data.rate ?? "" }}
            </div>
            <div
                class="col-2 border-b border-l border-r border-black border-solid"
            >
                {{ data.rate * data.quantity ?? "" }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-3 border-b border-l font-bold border-black border-solid"
            >
                No. OF PACKAGES
            </div>
            <div class="col-1 border-b border-l border-black border-solid">
                {{ props.invoice.no_packets ?? "No Packs" }}
            </div>
            <div
                class="col-3 border-b border-l font-bold border-black border-solid"
            >
                Total weight
            </div>
            <div class="col-1 border-b border-l border-black border-solid">
                {{ props.invoice.total_weight ?? "" }}
            </div>
            <div
                class="col-2 border-b border-l font-bold border-black border-solid"
            >
                Sub Total
            </div>
            <div
                class="col-2 border-b border-l border-r border-black border-solid"
            >
                ₹ {{ props.invoice.total_ammount ?? "" }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-4 border-b border-l font-bold border-black border-solid"
            >
                Amount in words
            </div>
            <div
                class="col-8 border-b border-l border-r border-black border-solid"
            >
                {{ amountInWords }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-6 border-b border-l font-bold border-black border-solid"
            >
                OUR BANK DETAILS
            </div>
            <div
                class="col-6 border-b border-l font-bold border-r border-black border-solid"
            >
                {{ props.invoice.company.company_name ?? "" }}
            </div>
        </div>
        <div class="row">
            <div
                class="col-6 border-b border-l font-bold border-black border-solid"
            >
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
            <div
                class="col-6 border-b border-l font-bold border-r border-black border-solid"
            >
                <img
                    class="img"
                    :src="props.invoice.company.sign ?? ''"
                    alt="jpeg"
                />
            </div>
        </div>
        <div class="row">
            <div
                class="col border-b height border-l font-bold border-black border-solid"
            >
                <img class="images" src="build/images/duroflex.png" alt="" />
            </div>
            <div
                class="col-2 border-b height border-l font-bold border-r border-black border-solid"
            >
                <img class="images" src="build/images/epson.png" alt="" />
            </div>
            <div
                class="col-2 border-b height font-bold border-black border-solid"
            >
                <img class="images" src="build/images/gobbler.jpg" alt="" />
            </div>
            <div
                class="col-2 border-b height border-l font-bold border-r border-black border-solid"
            >
                <img class="images" src="build/images/splashjet.png" alt="" />
            </div>
            <div
                class="col-2 border-b height font-bold border-black border-solid"
            >
                <img class="images" src="build/images/kangro.png" alt="" />
            </div>
            <div
                class="col-2 border-b height border-l font-bold border-r border-black border-solid"
            >
                <img class="images" src="build/images/compu.png" alt="" />
            </div>
        </div>
    </div>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
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
    "Zero",
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
    const decimalPartWords = convertToWordsRecursive(decimalPart);

    if (amountValue === 0) {
        amountInWords.value = "Zero Rupees";
    } else {
        amountInWords.value = `${convertToWordsRecursive(
            amountValue
        )} Rupees and ${decimalPartWords} Paise`;
    }
};

onMounted(() => {
    // Call the conversion function when the component is mounted
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
</style>
