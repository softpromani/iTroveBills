<template>
    <div class="mt-5 bg-purple-700 rounded-md position-relative py-2 px-3">
        <h2 class="text-sm font-medium text-center text-white font-weight-bolder mb-0">
            INVOICE
        </h2>
        <span
            class="text-white cursor-pointer top-2 right-3 position-absolute"
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

                            <!-- Description of Product - contenteditable -->
                            <td contenteditable="true"
                                :data-row="rowIndex"
                                :data-cell="0"
                                v-html="row[0]"
                                @blur="updateCell(rowIndex, 0, $event)">
                            </td>

                            <!-- HSN Code with Autocomplete -->
                            <td class="position-relative">
                                <input
                                    type="text"
                                    :value="row[1]"
                                    @input="handleHsnInput(rowIndex, $event)"
                                    @focus="showDropdown[rowIndex] = true"
                                    @blur="handleBlur(rowIndex)"
                                    class="form-control border-0 p-1"
                                    placeholder="Enter HSN Code"
                                    autocomplete="off"
                                />

                                <!-- Dropdown -->
                                <div
                                    v-if="showDropdown[rowIndex] && hsnResults[rowIndex] && hsnResults[rowIndex].length > 0"
                                    class="dropdown-menu show position-absolute w-100"
                                    style="top: 100%; z-index: 1000; max-height: 200px; overflow-y: auto;"
                                >
                                    <div
                                        v-for="item in hsnResults[rowIndex]"
                                        :key="item.code"
                                        @mousedown="selectHsnCode(rowIndex, item)"
                                        class="dropdown-item cursor-pointer hover-item"
                                        style="font-size: 12px; padding: 8px 12px;"
                                    >
                                        <strong>{{ item.code }}</strong> - {{ item.description }}
                                    </div>
                                </div>
                            </td>

                            <!-- Quantity - contenteditable -->
                            <td contenteditable="true"
                                :data-row="rowIndex"
                                :data-cell="2"
                                v-html="row[2]"
                                @blur="updateCell(rowIndex, 2, $event)">
                            </td>

                            <!-- Unit - contenteditable -->
                            <td contenteditable="true"
                                :data-row="rowIndex"
                                :data-cell="3"
                                v-html="row[3]"
                                @blur="updateCell(rowIndex, 3, $event)">
                            </td>

                            <!-- Weight - contenteditable -->
                            <td contenteditable="true"
                                :data-row="rowIndex"
                                :data-cell="4"
                                v-html="row[4]"
                                @blur="updateCell(rowIndex, 4, $event)">
                            </td>

                            <!-- Rate - contenteditable -->
                            <td contenteditable="true"
                                :data-row="rowIndex"
                                :data-cell="5"
                                v-html="row[5]"
                                @blur="updateCell(rowIndex, 5, $event)">
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
import axios from 'axios';

const form = useForm({});
const props = defineProps({
    invoiceitem: Object,
});

const tableData = ref([]);
const invoicedetails = ref([]);
const showproductCard = ref(true);

// HSN Code search related
const hsnResults = ref({});
const showDropdown = ref({});
const searchTimeout = ref(null);

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

// Handle HSN Code input with debouncing
const handleHsnInput = async (rowIndex, event) => {
    const query = event.target.value;
    tableData.value[rowIndex][1] = query;

    // Clear previous timeout
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value);
    }

    // If query is empty, hide dropdown
    if (!query.trim()) {
        showDropdown.value[rowIndex] = false;
        hsnResults.value[rowIndex] = [];
        return;
    }

    // Debounce search - wait 300ms after user stops typing
    searchTimeout.value = setTimeout(async () => {
        try {
            const response = await axios.get('/hsn/search', {
                params: { q: query }
            });

            hsnResults.value[rowIndex] = response.data;
            showDropdown.value[rowIndex] = true;
        } catch (error) {
            console.error('Error searching HSN codes:', error);
            hsnResults.value[rowIndex] = [];
        }
    }, 300);
};

// Select HSN Code from dropdown
const selectHsnCode = (rowIndex, item) => {
    tableData.value[rowIndex][1] = item.code;
    showDropdown.value[rowIndex] = false;
    hsnResults.value[rowIndex] = [];
};

// Handle input blur
const handleBlur = (rowIndex) => {
    // Delay hiding dropdown to allow click events on dropdown items
    setTimeout(() => {
        showDropdown.value[rowIndex] = false;
    }, 150);
};

// Add new row
const addRow = () => {
    tableData.value.push(["", "", "", "", "", ""]);
};

// Remove row
const removeRow = (rowIndex) => {
    if (tableData.value.length > 1) {
        tableData.value.splice(rowIndex, 1);

        // Clean up HSN search data for removed rows
        delete hsnResults.value[rowIndex];
        delete showDropdown.value[rowIndex];

        // Reindex remaining rows
        const newHsnResults = {};
        const newShowDropdown = {};

        tableData.value.forEach((_, index) => {
            if (hsnResults.value[index + (index >= rowIndex ? 1 : 0)]) {
                newHsnResults[index] = hsnResults.value[index + (index >= rowIndex ? 1 : 0)];
            }
            if (showDropdown.value[index + (index >= rowIndex ? 1 : 0)]) {
                newShowDropdown[index] = showDropdown.value[index + (index >= rowIndex ? 1 : 0)];
            }
        });

        hsnResults.value = newHsnResults;
        showDropdown.value = newShowDropdown;
    }
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
        route("customer.bill.update", {
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

<style scoped>
.font-bold {
    font-weight: 700;
}

td[contenteditable]:focus {
    outline: none;
}

.hover-item:hover {
    background-color: #f8f9fa;
}

.dropdown-menu {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.cursor-pointer {
    cursor: pointer;
}

/* Custom scrollbar for dropdown */
.dropdown-menu::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.dropdown-menu::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.dropdown-menu::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
