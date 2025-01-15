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
                            <td>{{ getSerialNumber(rowIndex) }}</td>
                            <td v-for="(cell, cellIndex) in row" :key="cellIndex"
                                @input="updateCell(rowIndex, cellIndex, $event)" contenteditable>
                                {{ cell }}
                            </td>
                            <td>{{ calculateTaxableValue(rowIndex) }}</td>
                            <td>
                                <span class="cursor-pointer badge rounded-pill text-bg-primary" @click="addRow"
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="font-bold">Total Weight:</td>
                            <td class="font-bold">
                                {{ calculateTotalWeight() }}
                            </td>
                            <td class="font-bold">Total:</td>
                            <td class="font-bold">
                                {{ calculateTotalTaxableValue() }}
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="text-white btn btn-info text-bold" @click="submitForm">
                Submit
            </button>
        </div>
    </transition>
</template>

<script setup>
import { ref } from "vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
const form = useForm({});

// Define the initial table data with one empty row
const tableData = ref([["", "", "", "", "", ""]]);

const invoicedetails = ref([]);
const showproductCard = ref(true);
// Function to update cell data
const updateCell = (rowIndex, cellIndex, event) => {
    // Validate if the input is a number for Quantity, Rate, and Weight cells
    if ([2, 4, 5].includes(cellIndex)) {
        // Replace these indices with the actual indices of Quantity, Rate, and Weight
        const cellValue = event.target.textContent;
        if (!isNaN(cellValue)) {
            // Update the cell with the numeric value
            tableData.value[rowIndex][cellIndex] = parseFloat(cellValue);
        }
        // If it's not a number, reset the cell content
        else {
            event.target.textContent =0;
        }
    } else {
        // For other cells, allow any input
        tableData.value[rowIndex][cellIndex] = event.target.textContent;
    }
};

// Function to add a new row
const addRow = (rowIndex) => {
    const newRow = ["", "", "", "", "", ""];
    tableData.value.splice(rowIndex + 1, 0, newRow);
};

// Function to remove a row
const removeRow = (rowIndex) => {
    tableData.value.splice(rowIndex, 1);
};

// Function to get the serial number based on the row index
const getSerialNumber = (rowIndex) => {
    return rowIndex + 1;
};

// Function to calculate the "Taxable Value" for a row
const calculateTaxableValue = (rowIndex) => {
    const quantity = parseFloat(tableData.value[rowIndex][2]) || 0;
    const rate = parseFloat(tableData.value[rowIndex][5]) || 0;
    return (quantity * rate).toFixed(2);
};

// Function to calculate the "Total Taxable Value"
const calculateTotalTaxableValue = () => {
    let total = 0;
    for (let rowIndex = 0; rowIndex < tableData.value.length; rowIndex++) {
        total += parseFloat(calculateTaxableValue(rowIndex));
    }
    return total.toFixed(2);
};

// Function to calculate the "Total Weight"
const calculateTotalWeight = () => {
    let totalWeight = 0;
    for (let rowIndex = 0; rowIndex < tableData.value.length; rowIndex++) {
        const weight = parseFloat(tableData.value[rowIndex][4]) || 0;
        totalWeight += weight;
    }
    return totalWeight.toFixed(2);
};

// Method to handle form submission
const submitForm = () => {
    const invoiceNoValue = document.getElementById("invoice_no").value;
    const customer = document.getElementById("customer").value;
    const company = document.getElementById("company").value;
    const vehical_no = document.getElementById("vehical_no").value;

    // Define the index where you want to push the data
    const indexToUpdate = 0; // Change this to the desired index

    // Push data to the specified index
    invoicedetails.value[indexToUpdate] = {
        invoice: invoiceNoValue,
        customer: customer,
        company: company,
        vehical_no: vehical_no,
        totalWeight: calculateTotalWeight(),
        totalTaxableValue: calculateTotalTaxableValue(),
    };

    // Get the updated table data and do something with it
    const updatedData = tableData.value;
    const updatedInvoiceDetails = invoicedetails.value;

    form.post(
        route("performa.customer.store.bill", {
            invoicedata: updatedData,
            invoicedetails: updatedInvoiceDetails,
        }),
        {
            preserveScroll: true,
        }
    );
};

const toggleproductCard = () => {
    showproductCard.value = !showproductCard.value;
};
</script>
