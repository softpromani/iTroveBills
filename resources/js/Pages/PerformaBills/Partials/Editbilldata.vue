<template>
    <div class="mt-5 bg-purple-700 rounded-md position-relative">
        <h2 class="font-medium text-center text-white font-weight-bolder">
            INVOICE
        </h2>
        <span
            class="text-white cursor-pointer top-2 right-2 position-absolute"
            @click="toggleproductCard"
        >
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

                            <!-- Editable cells -->
                            <td v-for="(cell, cellIndex) in row"
                                :key="cellIndex"
                                contenteditable="true"
                                :data-row="rowIndex"
                                :data-cell="cellIndex"
                                v-html="cell"
                                @blur="updateCell(rowIndex, cellIndex, $event)">
                            </td>

                            <!-- Taxable value column -->
                            <td>{{ calculateTaxableValue(rowIndex) }}</td>

                            <!-- Action column -->
                            <td>
                                <span
                                    class="cursor-pointer badge rounded-pill text-bg-primary"
                                    @click="addRow"
                                    v-if="rowIndex === tableData.length - 1">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </span>
                                <span
                                    class="cursor-pointer badge rounded-pill text-bg-danger"
                                    @click="removeRow(rowIndex)"
                                    v-else>
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </span>
                            </td>
                        </tr>

                        <!-- Totals row -->
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="font-bold">Total Weight:</td>
                            <td class="font-bold">{{ calculateTotalWeight() }}</td>
                            <td class="font-bold">Total:</td>
                            <td class="font-bold">{{ calculateTotalTaxableValue() }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button class="text-white btn btn-info text-bold" @click="updatebill">
                Submit
            </button>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({});
const props = defineProps({
    invoiceitem: Object,
});

const tableData = ref([]);
const invoicedetails = ref([]);
const showproductCard = ref(true);

// Fill initial table data
onMounted(() => {
    if (props.invoiceitem) {
        tableData.value = props.invoiceitem.map((item) => [
            item.desc_product,
            item.hsn_code,
            item.quantity,
            item.unit,
            item.weight,
            item.rate,
        ]);
    } else {
        tableData.value = [["", "", "", "", "", ""]];
    }
});

// Update cell value
const updateCell = (rowIndex, cellIndex, event) => {
    let cellValue = event.target.innerText.trim();

    if ([2, 4, 5].includes(cellIndex)) {
        cellValue = parseFloat(cellValue) || 0;
    }
    tableData.value[rowIndex][cellIndex] = cellValue;
};

// Add new row
const addRow = () => {
    tableData.value.push(["", "", "", "", "", ""]);
};

// Remove row
const removeRow = (rowIndex) => {
    tableData.value.splice(rowIndex, 1);
};

// Serial number
const getSerialNumber = (rowIndex) => rowIndex + 1;

// Taxable value for a row
const calculateTaxableValue = (rowIndex) => {
    const quantity = parseFloat(tableData.value[rowIndex][2]) || 0;
    const rate = parseFloat(tableData.value[rowIndex][5]) || 0;
    return (quantity * rate).toFixed(2);
};

// Total taxable value
const calculateTotalTaxableValue = () => {
    return tableData.value.reduce((total, row, index) => {
        return total + parseFloat(calculateTaxableValue(index));
    }, 0).toFixed(2);
};

// Total weight
const calculateTotalWeight = () => {
    return tableData.value.reduce((total, row) => {
        return total + (parseFloat(row[4]) || 0);
    }, 0).toFixed(2);
};

// Submit invoice
const updatebill = () => {
    const invoiceNoValue = document.getElementById("invoice_no")?.value || "";
    const vehical_no = document.getElementById("vehical_no")?.value || "";

    invoicedetails.value[0] = {
        invoice: invoiceNoValue,
        vehical_no: vehical_no,
        totalWeight: calculateTotalWeight(),
        totalTaxableValue: calculateTotalTaxableValue(),
    };

    form.post(
        route("performa.customer.bill.update", {
            invoicedata: tableData.value,
            invoice_id: props.invoiceitem?.[0]?.invoice_id,
            invoicedetails: invoicedetails.value,
        }),
        { preserveScroll: true }
    );
};

// Toggle product card
const toggleproductCard = () => {
    showproductCard.value = !showproductCard.value;
};
</script>
