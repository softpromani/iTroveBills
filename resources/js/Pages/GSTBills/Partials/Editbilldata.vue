<template>
    <div class="mt-5 bg-purple-700 rounded-md position-relative">
        <h2 class="font-medium text-center text-white font-weight-bolder">
            EDIT INVOICE
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
                            <th>GST %</th>
                            <th>GST Amount</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, rowIndex) in tableData" :key="`row-${rowIndex}`">
                            <td>{{ rowIndex + 1 }}</td>
                            <td>
                                <input
                                    type="text"
                                    :value="row[0]"
                                    @input="updateCell(rowIndex, 0, $event)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 0)"
                                    class="form-control border-0 p-1"
                                    placeholder="Enter description"
                                />
                            </td>
                            <td class="position-relative">
                                <input
                                    type="text"
                                    :value="row[1]"
                                    @input="handleHsnInput(rowIndex, $event)"
                                    @focus="showDropdown[rowIndex] = true"
                                    @blur="handleHsnBlur(rowIndex)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 1)"
                                    @keydown.enter="selectFirstHsnResult(rowIndex)"
                                    @keydown.escape="hideHsnDropdown(rowIndex)"
                                    class="form-control border-0 p-1"
                                    placeholder="Enter HSN Code"
                                    autocomplete="off"
                                    :ref="el => setFieldRef(`hsn-${rowIndex}`, el)"
                                />
                                <div
                                    v-if="showDropdown[rowIndex] && hsnResults[rowIndex] && hsnResults[rowIndex].length > 0"
                                    class="dropdown-menu show position-absolute w-100"
                                    style="top: 100%; z-index: 1000; max-height: 200px; overflow-y: auto;"
                                >
                                    <div
                                        v-for="(item, index) in hsnResults[rowIndex]"
                                        :key="item.code"
                                        @mousedown.prevent="selectHsnCode(rowIndex, item)"
                                        class="dropdown-item cursor-pointer hover-item"
                                        :class="{ 'active': index === 0 }"
                                        style="font-size: 12px; padding: 8px 12px;"
                                    >
                                        <strong>{{ item.code }}</strong> - {{ item.description }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    :value="row[2]"
                                    @input="updateCell(rowIndex, 2, $event)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 2)"
                                    class="form-control border-0 p-1"
                                    placeholder="0"
                                    :ref="el => setFieldRef(`quantity-${rowIndex}`, el)"
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    :value="row[3]"
                                    @input="updateCell(rowIndex, 3, $event)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 3)"
                                    class="form-control border-0 p-1"
                                    placeholder="Enter unit"
                                    :ref="el => setFieldRef(`unit-${rowIndex}`, el)"
                                />
                            </td>
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    :value="row[4]"
                                    @input="updateCell(rowIndex, 4, $event)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 4)"
                                    class="form-control border-0 p-1"
                                    placeholder="0"
                                    :ref="el => setFieldRef(`weight-${rowIndex}`, el)"
                                />
                            </td>
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    :value="row[5]"
                                    @input="updateCell(rowIndex, 5, $event)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 5)"
                                    class="form-control border-0 p-1"
                                    placeholder="0"
                                    :ref="el => setFieldRef(`rate-${rowIndex}`, el)"
                                />
                            </td>
                            <td class="text-end">{{ calculateTaxableValue(rowIndex) }}</td>
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    :value="row[6]"
                                    @input="handleGstPercentageInput(rowIndex, $event)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 6)"
                                    class="form-control border-0 p-1"
                                    placeholder="0"
                                    :ref="el => setFieldRef(`gst-percentage-${rowIndex}`, el)"
                                />
                            </td>
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    :value="row[7]"
                                    @input="handleGstAmountInput(rowIndex, $event)"
                                    @keydown.tab="handleTabNavigation($event, rowIndex, 7)"
                                    class="form-control border-0 p-1"
                                    placeholder="0"
                                    :ref="el => setFieldRef(`gst-amount-${rowIndex}`, el)"
                                />
                            </td>
                            <td class="text-end">{{ calculateSubtotalAmount(rowIndex) }}</td>
                            <td>
                                <span class="cursor-pointer badge rounded-pill text-bg-primary me-2" @click="addRow(rowIndex)">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </span>
                                <span class="cursor-pointer badge rounded-pill text-bg-danger" @click="removeRow(rowIndex)" v-if="tableData.length > 1">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </span>
                            </td>
                        </tr>
                        <tr class="table-info">
                            <td colspan="5" class="text-end font-bold">TOTALS:</td>
                            <td class="font-bold text-end">{{ calculateTotalWeight() }}</td>
                            <td></td>
                            <td class="font-bold text-end">{{ calculateTotalTaxableValue() }}</td>
                            <td></td>
                            <td class="font-bold text-end">{{ calculateTotalGstAmount() }}</td>
                            <td class="font-bold text-end">{{ calculateGrandTotal() }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button
                    class="btn btn-info text-white text-bold"
                    @click="updatebill"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Updating...</span>
                    <span v-else>Update Invoice</span>
                </button>
                <button class="btn btn-secondary" @click="cancelUpdate">
                    Cancel
                </button>
            </div>

            <!-- Display errors if any -->
            <div v-if="Object.keys(form.errors).length > 0" class="alert alert-danger mt-3">
                <h6>Please fix the following errors:</h6>
                <ul class="mb-0">
                    <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
                </ul>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted, nextTick, watch, reactive } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import axios from 'axios';

// Define props to receive data from parent
const props = defineProps({
    invoicedetails: Object,
    invoiceitem: Array,
    invoiceId: [String, Number],
});

// Initialize form data using useForm with correct route
const form = useForm({
    invoicedata: [],
    invoicedetails: [],
    invoice_id: null,
});

// State for the table data
const tableData = ref([]);
const showproductCard = ref(true);

// HSN Code search related
const hsnResults = ref({});
const showDropdown = ref({});
const searchTimeout = ref(null);
const fieldRefs = ref({});

// Add loading state
const isLoading = ref(false);

const setFieldRef = (key, el) => {
    if (el) {
        fieldRefs.value[key] = el;
    }
};

// Fill initial data on component mount
onMounted(() => {
    console.log('Mounted - Props received:', {
        invoicedetails: props.invoicedetails,
        invoiceitem: props.invoiceitem,
        invoiceId: props.invoiceId
    });

    // Set the invoice ID
    form.invoice_id = props.invoiceId || props.invoicedetails?.id;

    // Populate table with line items from parent prop
    if (props.invoiceitem && props.invoiceitem.length > 0) {
        tableData.value = props.invoiceitem.map((item) => [
            item.desc_product || "",
            item.hsn_code || "",
            parseFloat(item.quantity) || 0,
            item.unit || "",
            parseFloat(item.weight) || 0,
            parseFloat(item.rate) || 0,
            parseFloat(item.gst_percentage) || 0,
            parseFloat(item.gst_amount) || 0,
        ]);
    } else {
        tableData.value = [["", "", 0, "", 0, 0, 0, 0]];
    }

    console.log('Initial table data:', tableData.value);
});

// Watch for changes in props
watch(() => props.invoiceitem, (newItems) => {
    if (newItems && newItems.length > 0) {
        tableData.value = newItems.map((item) => [
            item.desc_product || "",
            item.hsn_code || "",
            parseFloat(item.quantity) || 0,
            item.unit || "",
            parseFloat(item.weight) || 0,
            parseFloat(item.rate) || 0,
            parseFloat(item.gst_percentage) || 0,
            parseFloat(item.gst_amount) || 0,
        ]);
    }
}, { deep: true });

const toggleproductCard = () => {
    showproductCard.value = !showproductCard.value;
};

// Handle tab navigation between fields
const handleTabNavigation = (event, rowIndex, currentField) => {
    const fieldNames = ['description', 'hsn', 'quantity', 'unit', 'weight', 'rate', 'gst-percentage', 'gst-amount'];

    if (event.shiftKey) {
        // Shift+Tab - go to previous field
        event.preventDefault();
        focusPreviousField(rowIndex, currentField);
    } else {
        // Tab - go to next field
        event.preventDefault();
        focusNextField(rowIndex, currentField);
    }
};

// Focus next field
const focusNextField = (rowIndex, currentField) => {
    const fieldNames = ['description', 'hsn', 'quantity', 'unit', 'weight', 'rate', 'gst-percentage', 'gst-amount'];

    if (currentField < fieldNames.length - 1) {
        // Move to next field in same row
        const nextFieldKey = `${fieldNames[currentField + 1]}-${rowIndex}`;
        nextTick(() => {
            if (fieldRefs.value[nextFieldKey]) {
                fieldRefs.value[nextFieldKey].focus();
            }
        });
    } else if (rowIndex < tableData.value.length - 1) {
        // Move to first field of next row
        const nextFieldKey = `${fieldNames[0]}-${rowIndex + 1}`;
        nextTick(() => {
            if (fieldRefs.value[nextFieldKey]) {
                fieldRefs.value[nextFieldKey].focus();
            }
        });
    } else {
        // Add new row and focus first field
        addRow(rowIndex);
        nextTick(() => {
            const newRowIndex = rowIndex + 1;
            const firstFieldKey = `${fieldNames[0]}-${newRowIndex}`;
            if (fieldRefs.value[firstFieldKey]) {
                fieldRefs.value[firstFieldKey].focus();
            }
        });
    }
};

// Focus previous field
const focusPreviousField = (rowIndex, currentField) => {
    const fieldNames = ['description', 'hsn', 'quantity', 'unit', 'weight', 'rate', 'gst-percentage', 'gst-amount'];

    if (currentField > 0) {
        // Move to previous field in same row
        const prevFieldKey = `${fieldNames[currentField - 1]}-${rowIndex}`;
        nextTick(() => {
            if (fieldRefs.value[prevFieldKey]) {
                fieldRefs.value[prevFieldKey].focus();
            }
        });
    } else if (rowIndex > 0) {
        // Move to last field of previous row
        const prevFieldKey = `${fieldNames[fieldNames.length - 1]}-${rowIndex - 1}`;
        nextTick(() => {
            if (fieldRefs.value[prevFieldKey]) {
                fieldRefs.value[prevFieldKey].focus();
            }
        });
    }
};

// Update cell with proper type conversion
const updateCell = (rowIndex, cellIndex, event) => {
    let value = event.target.value;

    // Convert numeric fields to numbers
    if ([2, 4, 5, 6, 7].includes(cellIndex)) {
        const numValue = parseFloat(value);
        value = isNaN(numValue) ? 0 : numValue;
    }

    // Update the data
    tableData.value[rowIndex][cellIndex] = value;

    // Trigger recalculation for quantity, rate changes
    if ([2, 5].includes(cellIndex)) {
        recalculateGst(rowIndex);
    }
};

// Handle HSN Code input with debouncing
const handleHsnInput = async (rowIndex, event) => {
    const query = event.target.value;
    updateCell(rowIndex, 1, event);

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

// Handle HSN blur with delay for dropdown selection
const handleHsnBlur = (rowIndex) => {
    setTimeout(() => {
        showDropdown.value[rowIndex] = false;
    }, 200);
};

// Hide HSN dropdown
const hideHsnDropdown = (rowIndex) => {
    showDropdown.value[rowIndex] = false;
};

// Select first HSN result on Enter
const selectFirstHsnResult = (rowIndex) => {
    if (hsnResults.value[rowIndex] && hsnResults.value[rowIndex].length > 0) {
        selectHsnCode(rowIndex, hsnResults.value[rowIndex][0]);
    }
};

// Select HSN Code from dropdown
const selectHsnCode = (rowIndex, item) => {
    tableData.value[rowIndex][1] = item.code;
    showDropdown.value[rowIndex] = false;
    hsnResults.value[rowIndex] = [];

    // Focus next field
    nextTick(() => {
        const nextFieldKey = `quantity-${rowIndex}`;
        if (fieldRefs.value[nextFieldKey]) {
            fieldRefs.value[nextFieldKey].focus();
        }
    });
};

// Handle GST Percentage input
const handleGstPercentageInput = (rowIndex, event) => {
    updateCell(rowIndex, 6, event);
    recalculateGstFromPercentage(rowIndex);
};

// Handle GST Amount input
const handleGstAmountInput = (rowIndex, event) => {
    updateCell(rowIndex, 7, event);
    recalculateGstFromAmount(rowIndex);
};

// Recalculate GST from percentage
const recalculateGstFromPercentage = (rowIndex) => {
    const gstPercentage = parseFloat(tableData.value[rowIndex][6]) || 0;
    const taxableValue = parseFloat(calculateTaxableValue(rowIndex)) || 0;
    const gstAmount = (taxableValue * gstPercentage) / 100;
    tableData.value[rowIndex][7] = parseFloat(gstAmount.toFixed(2));
};

// Recalculate GST from amount
const recalculateGstFromAmount = (rowIndex) => {
    const gstAmount = parseFloat(tableData.value[rowIndex][7]) || 0;
    const taxableValue = parseFloat(calculateTaxableValue(rowIndex)) || 0;

    if (taxableValue > 0) {
        const gstPercentage = (gstAmount / taxableValue) * 100;
        tableData.value[rowIndex][6] = parseFloat(gstPercentage.toFixed(2));
    }
};

// Recalculate GST when quantity or rate changes
const recalculateGst = (rowIndex) => {
    const gstPercentage = parseFloat(tableData.value[rowIndex][6]) || 0;
    if (gstPercentage > 0) {
        recalculateGstFromPercentage(rowIndex);
    }
};

// Add new row
const addRow = (rowIndex) => {
    const newRow = ["", "", 0, "", 0, 0, 0, 0];
    tableData.value.splice(rowIndex + 1, 0, newRow);

    // Update field references for new row
    nextTick(() => {
        // The refs will be automatically created when the new row renders
    });
};

// Remove row
const removeRow = (rowIndex) => {
    if (tableData.value.length > 1) {
        tableData.value.splice(rowIndex, 1);

        // Clean up references and dropdown data
        Object.keys(fieldRefs.value).forEach(key => {
            if (key.includes(`-${rowIndex}`)) {
                delete fieldRefs.value[key];
            }
        });

        delete hsnResults.value[rowIndex];
        delete showDropdown.value[rowIndex];
    }
};

// Calculate taxable value for a row
const calculateTaxableValue = (rowIndex) => {
    const quantity = parseFloat(tableData.value[rowIndex][2]) || 0;
    const rate = parseFloat(tableData.value[rowIndex][5]) || 0;
    return (quantity * rate).toFixed(2);
};

// Calculate subtotal amount (Taxable Value + GST Amount)
const calculateSubtotalAmount = (rowIndex) => {
    const taxableValue = parseFloat(calculateTaxableValue(rowIndex)) || 0;
    const gstAmount = parseFloat(tableData.value[rowIndex][7]) || 0;
    return (taxableValue + gstAmount).toFixed(2);
};

// Calculate total weight
const calculateTotalWeight = () => {
    return tableData.value
        .reduce((total, row) => total + (parseFloat(row[4]) || 0), 0)
        .toFixed(2);
};

// Calculate total taxable value
const calculateTotalTaxableValue = () => {
    return tableData.value
        .reduce((total, row, index) => total + parseFloat(calculateTaxableValue(index)), 0)
        .toFixed(2);
};

// Calculate total GST amount
const calculateTotalGstAmount = () => {
    return tableData.value
        .reduce((total, row) => total + (parseFloat(row[7]) || 0), 0)
        .toFixed(2);
};

// Calculate grand total (sum of all subtotals)
const calculateGrandTotal = () => {
    return tableData.value
        .reduce((total, row, index) => total + parseFloat(calculateSubtotalAmount(index)), 0)
        .toFixed(2);
};

// Submit invoice update
const updatebill = () => {
    console.log('Update bill function called');
    console.log('Current form data:', form);
    console.log('Current table data:', tableData.value);
    console.log('Invoice details from props:', props.invoicedetails);

    // Validation
    if (!props.invoicedetails?.invoice_no && !form.invoice_no) {
        alert('Invoice number is required');
        return;
    }

    if (!props.invoicedetails?.customer && !form.customer) {
        alert('Customer is required');
        return;
    }

    if (!props.invoicedetails?.company && !form.company) {
        alert('Company is required');
        return;
    }

    if (!props.invoicedetails?.vehical_no && !form.vehical_no) {
        alert('Vehicle number is required');
        return;
    }

    const hasValidData = tableData.value.some(row =>
        row[0].trim() !== '' || row[1].trim() !== '' || parseFloat(row[2]) > 0
    );

    if (!hasValidData) {
        alert('Please add at least one item to the invoice');
        return;
    }

    // Clear previous errors
    form.clearErrors();

    // Set data for submission
    form.invoicedata = tableData.value;
    form.invoicedetails = [{
        invoice: props.invoicedetails?.invoice_no || form.invoice_no,
        customer: props.invoicedetails?.customer || form.customer,
        company: props.invoicedetails?.company || form.company,
        vehical_no: props.invoicedetails?.vehical_no || form.vehical_no,
        totalWeight: calculateTotalWeight(),
        totalTaxableValue: calculateTotalTaxableValue(),
        totalGstAmount: calculateTotalGstAmount(),
        grandTotal: calculateGrandTotal(),
    }];

    console.log('Submitting data:', {
        invoicedata: form.invoicedata,
        invoicedetails: form.invoicedetails,
        invoice_id: form.invoice_id
    });

    // Set loading state
    isLoading.value = true;

    // Post data to the controller
    form.post(route("gst.customer.bill.update", { invoice_id: form.invoice_id }), {
        preserveScroll: true,
        onSuccess: (response) => {
            console.log('Update successful:', response);
            alert('Invoice updated successfully!');
            // Redirect to invoice list or stay on page
            router.visit(route('gst.invoice.list'));
        },
        onError: (errors) => {
            console.error('Update errors:', errors);
            alert('Error updating invoice. Please check the form and try again.');
        },
        onFinish: () => {
            isLoading.value = false;
        }
    });
};

// Cancel update
const cancelUpdate = () => {
    if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
        router.visit(route('gst.invoice.list'));
    }
};
</script>

<style scoped>
.font-bold {
    font-weight: 700;
}

.hover-item:hover {
    background-color: #f8f9fa;
}

.dropdown-menu {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item.active {
    background-color: #007bff;
    color: white;
}

.cursor-pointer {
    cursor: pointer;
}

.table-info {
    background-color: #d1ecf1 !important;
}

.text-end {
    text-align: right;
}

.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}
.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
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

/* Remove focus outline from inputs */
.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #80bdff;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
