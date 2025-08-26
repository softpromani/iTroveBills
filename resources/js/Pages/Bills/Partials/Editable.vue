<template>
    <div class="mt-5 bg-purple-700 rounded-md position-relative">
        <h2 class="font-medium text-center text-white font-weight-bolder">
            INVOICE
        </h2>
        <span class="text-white cursor-pointer top-2 right-2 position-absolute" @click="toggleproductCard">
            <i class="fa-solid fa-bullseye"></i>
        </span>
    </div>

    <transition name="slide-fade">
        <div v-if="showproductCard">
            <div class="-mt-4 rounded-lg shadow table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>S.NO.</th>
                            <th>Description of Product</th>
                            <th>HSN Code</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Weight</th>
                            <th>Rate</th>
                            <th>Taxable Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, rowIndex) in tableData" :key="rowIndex">
                            <td>{{ rowIndex + 1 }}</td>
                            <td v-for="(cell, cellIndex) in row" :key="cellIndex"
                                contenteditable
                                @input="updateCell(rowIndex, cellIndex, $event)"
                                @keydown="saveCursor($event)">
                            </td>
                            <td>{{ calculateTaxableValue(rowIndex) }}</td>
                            <td>
                                <span class="cursor-pointer badge rounded-pill text-bg-primary" @click="addRow(rowIndex)"
                                    v-if="rowIndex === tableData.length - 1">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </span>
                                <span class="cursor-pointer badge rounded-pill text-bg-danger" @click="removeRow(rowIndex)"
                                    v-else>
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4"></td>
                            <td class="font-bold">Total Weight:</td>
                            <td class="font-bold">{{ calculateTotalWeight() }}</td>
                            <td class="font-bold">Total:</td>
                            <td class="font-bold">{{ calculateTotalTaxableValue() }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button class="text-white btn btn-info text-bold mt-3" @click="submitForm">
                Submit
            </button>
        </div>
    </transition>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({});

// Initial table data (Description, HSN, Quantity, Unit, Weight, Rate)
const tableData = ref([["", "", "", "", "", ""]]);
const invoicedetails = ref([]);
const showproductCard = ref(true);

// Toggle product card
const toggleproductCard = () => {
    showproductCard.value = !showproductCard.value;
};

// Update contenteditable cell without re-rendering
const updateCell = (rowIndex, cellIndex, event) => {
    let value = event.target.innerText;

    // Numeric validation for Quantity, Weight, Rate (columns 2,4,5)
    if ([2, 4, 5].includes(cellIndex)) {
        value = parseFloat(value);
        if (isNaN(value)) value = 0;
    }

    tableData.value[rowIndex][cellIndex] = value;
};

// Save cursor position (optional, in case you update DOM programmatically)
const saveCursor = (event) => {
    const sel = window.getSelection();
    if (!sel.rangeCount) return;
    const range = sel.getRangeAt(0);
    event.target.dataset.cursorStart = range.startOffset;
};

// Add new row
const addRow = (rowIndex) => {
    const newRow = ["", "", "", "", "", ""];
    tableData.value.splice(rowIndex + 1, 0, newRow);
};

// Remove row
const removeRow = (rowIndex) => {
    if (tableData.value.length > 1) {
        tableData.value.splice(rowIndex, 1);
    }
};

// Calculate taxable value for a row
const calculateTaxableValue = (rowIndex) => {
    const quantity = parseFloat(tableData.value[rowIndex][2]) || 0;
    const rate = parseFloat(tableData.value[rowIndex][5]) || 0;
    return (quantity * rate).toFixed(2);
};

// Calculate total taxable value
const calculateTotalTaxableValue = () => {
    return tableData.value
        .reduce((total, row, index) => total + parseFloat(calculateTaxableValue(index)), 0)
        .toFixed(2);
};

// Calculate total weight
const calculateTotalWeight = () => {
    return tableData.value
        .reduce((total, row) => total + (parseFloat(row[4]) || 0), 0)
        .toFixed(2);
};

// Submit form
const submitForm = () => {
    const invoiceNoValue = document.getElementById("invoice_no")?.value || "";
    const customer = document.getElementById("customer")?.value || "";
    const company = document.getElementById("company")?.value || "";
    const vehical_no = document.getElementById("vehical_no")?.value || "";

    invoicedetails.value[0] = {
        invoice: invoiceNoValue,
        customer: customer,
        company: company,
        vehical_no: vehical_no,
        totalWeight: calculateTotalWeight(),
        totalTaxableValue: calculateTotalTaxableValue(),
    };

    form.post(
        route("customer.store.bill", {
            invoicedata: tableData.value,
            invoicedetails: invoicedetails.value,
        }),
        {
            preserveScroll: true,
        }
    );
};
</script>

<style scoped>
.font-bold {
    font-weight: 700;
}
td[contenteditable]:focus {
    outline: none;
}
</style>
