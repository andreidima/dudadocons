<template>
    <!-- <div class="row justify-content-around rounded-3" style="background-color: rgba(0, 191, 255, 0.4); border-left:6px solid; border-color:#2196F3; border-radius: 0px 0px 0px 0px"> -->
    <div class="row">
        <!-- <div class="col-lg-6 mb-4" style="position: relative;" v-click-out="hideAutocomplete"> -->
        <div class="col-lg-6" style="position: relative;" v-click-out="hideAutocomplete">
            <!-- Label (optional) -->
            <label class="mb-0 ps-3" for="cautaMembri"><b>Caută membri</b></label>

            <!-- The Input Group for Searching -->
            <div class="input-group">
            <input
                id="cautaMembri"
                ref="searchInput"
                type="text"
                class="form-control bg-white rounded-3"
                v-model="searchQuery"
                @focus="showAutocomplete = true"
                @input="onSearch"
                placeholder="Caută membri..."
            />
            </div>

            <!-- Autocomplete/Results Dropdown -->
            <div
                v-if="showAutocomplete && filteredMembri.length"
                class="bg-white border"
                style="position:absolute; z-index:1000; width:100%; max-height:220px; overflow:auto;"
                @click.stop
                >
                    <button
                        v-for="membru in filteredMembri"
                        :key="membru.id"
                        type="button"
                        class="list-group-item list-group-item-action py-1 border-bottom px-2 text-primary"
                        @click.stop="addMembru(membru)"
                    >
                        {{ membru.full_name }}
                    </button>
            </div>

            <!-- Show a note if no results -->
            <div
            v-else-if="showAutocomplete && !filteredMembri.length"
            class="bg-white border p-2"
            style="position:absolute; z-index:1000; width:100%;"
            @click.stop
            >
            <small>Niciun membru găsit</small>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Selected Membri List (2nd list) -->
            <label class="mb-0 ps-3" for="cautaMembri"><b>Membri adăugați</b></label>
            <div class="">
            <table class="table table-sm mb-0">
                <tbody>
                <tr
                    v-for="(membru, index) in selectedMembri"
                    :key="membru.id"
                >
                    <td class="d-flex justify-content-between">
                        {{ membru.full_name }}
                        <!-- Hidden input for normal form submission -->
                        <input
                            type="hidden"
                            :name="`membri_ids[${index}]`"
                            :value="membru.id"
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
  name: 'MembriSelector',
  props: {
    allMembri: { type: Array, default: () => [] },
    existingMembri: { type: Array, default: () => [] },
  },
  data() {
    return {
      selectedMembri: [],
      searchQuery: '',
      showAutocomplete: false,
    };
  },
  computed: {
    filteredMembri() {
      const lowerQ = this.searchQuery.toLowerCase().trim();
      return this.allMembri
        .filter(m => !this.selectedMembri.some(sel => sel.id === m.id))
        .filter(m => m.full_name.toLowerCase().includes(lowerQ));
    },
  },
  mounted() {
    this.selectedMembri = [...this.existingMembri];
  },
  methods: {
    onSearch() {
      this.showAutocomplete = !!this.searchQuery;
    },
    addMembru(membru) {
      this.selectedMembri.push(membru);
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
      this.selectedMembri.splice(index, 1);
    },
    hideAutocomplete() {
      this.showAutocomplete = false;
    },
  },
};
</script>
