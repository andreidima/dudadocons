<template>
    <!-- <div class="row justify-content-around rounded-3" style="background-color: rgba(0, 191, 255, 0.4); border-left:6px solid; border-color:#2196F3; border-radius: 0px 0px 0px 0px"> -->
    <div class="row">
        <!-- <div class="col-lg-6 mb-4" style="position: relative;" v-click-out="hideAutocomplete"> -->
        <div class="col-lg-6" style="position: relative;" v-click-out="hideAutocomplete">
            <!-- Label (optional) -->
            <label class="mb-0 ps-3" for="cautaSubcontractanti"><b>Caută subcontractanți</b></label>

            <!-- The Input Group for Searching -->
            <div class="input-group">
            <input
                id="cautaSubcontractanti"
                ref="searchInput"
                type="text"
                class="form-control bg-white rounded-3"
                v-model="searchQuery"
                @focus="showAutocomplete = true"
                @input="onSearch"
                placeholder="Caută subcontractanți..."
            />
            </div>

            <!-- Autocomplete/Results Dropdown -->
            <div
                v-if="showAutocomplete && filteredSubcontractanti.length"
                class="bg-white border"
                style="position:absolute; z-index:1000; width:100%; max-height:220px; overflow:auto;"
                @click.stop
                >
                    <button
                        v-for="subcontractant in filteredSubcontractanti"
                        :key="subcontractant.id"
                        type="button"
                        class="list-group-item list-group-item-action py-1 border-bottom px-2 text-primary"
                        @click.stop="addMembru(subcontractant)"
                    >
                        {{ subcontractant.nume }}
                    </button>
            </div>

            <!-- Show a note if no results -->
            <div
            v-else-if="showAutocomplete && !filteredSubcontractanti.length"
            class="bg-white border p-2"
            style="position:absolute; z-index:1000; width:100%;"
            @click.stop
            >
            <small>Niciun subcontractant găsit</small>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Selected Subcontractanti List (2nd list) -->
            <label class="mb-0 ps-3" for="cautaSubcontractanti"><b>Subcontractanti adăugați</b></label>
            <div class="">
            <table class="table table-sm mb-0">
                <tbody>
                <tr
                    v-for="(subcontractant, index) in selectedSubcontractanti"
                    :key="subcontractant.id"
                >
                    <td class="d-flex justify-content-between">
                        {{ subcontractant.nume }}
                        <!-- Hidden input for normal form submission -->
                        <input
                            type="hidden"
                            :name="`subcontractanti_ids[${index}]`"
                            :value="subcontractant.id"
                        />

                        <button
                            type="button"
                            class="btn btn-danger btn-sm px-1 py-0"
                            @click="removeMembru(index)"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>

    </div>
</template>

<script>
export default {
  name: 'SubcontractantiSelector',
  props: {
    allSubcontractanti: { type: Array, default: () => [] },
    existingSubcontractanti: { type: Array, default: () => [] },
  },
  data() {
    return {
      selectedSubcontractanti: [],
      searchQuery: '',
      showAutocomplete: false,
    };
  },
  computed: {
    filteredSubcontractanti() {
      const lowerQ = this.searchQuery.toLowerCase().trim();
      return this.allSubcontractanti
        .filter(m => !this.selectedSubcontractanti.some(sel => sel.id === m.id))
        .filter(m => m.nume.toLowerCase().includes(lowerQ));
    },
  },
  mounted() {
    this.selectedSubcontractanti = [...this.existingSubcontractanti];
  },
  methods: {
    onSearch() {
      this.showAutocomplete = !!this.searchQuery;
    },
    addMembru(subcontractant) {
      this.selectedSubcontractanti.push(subcontractant);
      this.searchQuery = '';          // Clear the search input
      // Optionally, you can keep the dropdown open or close it:
      // For continuous entry, you might want to keep it open.
      // Here, we leave it open and set focus back to the input.
      this.$nextTick(() => {
        if (this.$refs.searchInput) {
          this.$refs.searchInput.focus();
        }
      });
    },
    removeMembru(index) {
      this.selectedSubcontractanti.splice(index, 1);
    },
    hideAutocomplete() {
      this.showAutocomplete = false;
    },
  },
};
</script>
