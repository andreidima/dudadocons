<template>
    <!-- <div class="row justify-content-around rounded-3" style="background-color: rgba(0, 191, 255, 0.4); border-left:6px solid; border-color:#2196F3; border-radius: 0px 0px 0px 0px"> -->
    <div class="row">
        <!-- <div class="col-lg-6 mb-4" style="position: relative;" v-click-out="hideAutocomplete"> -->
        <div class="col-lg-4 mb-4" style="position: relative;" v-click-out="hideAutocomplete">
            <!-- Label (optional) -->
            <label class="mb-0 ps-3" for="cautaClienti"><b>Caută clienti</b></label>

            <!-- The Input Group for Searching -->
            <div class="input-group">
            <input
                id="cautaClienti"
                ref="searchInput"
                type="text"
                class="form-control bg-white rounded-3"
                v-model="searchQuery"
                @focus="showAutocomplete = true"
                @input="onSearch"
                placeholder="Caută clienti..."
            />
            </div>

            <!-- Autocomplete/Results Dropdown -->
            <div
                v-if="showAutocomplete && filteredClienti.length"
                class="bg-white border"
                style="position:absolute; z-index:1000; width:100%; max-height:220px; overflow:auto;"
                @click.stop
                >
                    <button
                        v-for="client in filteredClienti"
                        :key="client.id"
                        type="button"
                        class="list-group-item list-group-item-action py-1 border-bottom px-2 text-primary"
                        @click.stop="addMembru(client)"
                    >
                        {{ client.nume }}
                    </button>
            </div>

            <!-- Show a note if no results -->
            <div
            v-else-if="showAutocomplete && !filteredClienti.length"
            class="bg-white border p-2"
            style="position:absolute; z-index:1000; width:100%;"
            @click.stop
            >
            <small>Nici un client găsit</small>
            </div>
        </div>
        <div class="col-lg-8">
            <!-- Selected Clienti List (2nd list) -->
            <label class="mb-0 ps-3" for="cautaClienti"><b>Clienti adăugați</b></label>
            <div class="table-responsive">
            <table class="table table-sm table-striped table-hover rounded">
                <thead class="thead-light">
                    <tr>
                        <th>Nume</th>
                        <th>Observații</th>
                        <th class="text-end">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="" v-for="(client, index) in selectedClienti" :key="client.id">
                        <td class="align-middle">
                            {{ client.nume }}
                            <!-- Hidden input for member ID -->
                            <input
                                type="hidden"
                                :name="`clienti[${index}][id]`"
                                :value="client.id"
                            />
                        </td>

                        <td>
                            <!-- Text input for observatii -->
                            <input type="text"
                                :name="`clienti[${index}][observatii]`"
                                v-model="client.observatii"
                                placeholder=""
                                class="form-control form-control-sm" />
                        </td>

                        <td class="text-end">
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
  name: 'ClientiSelector',
  props: {
    allClienti: { type: Array, default: () => [] },
    existingClienti: { type: Array, default: () => [] },
  },
  data() {
    return {
      selectedClienti: [],
      searchQuery: '',
      showAutocomplete: false,
    };
  },
  computed: {
    filteredClienti() {
      const lowerQ = this.searchQuery.toLowerCase().trim();
      return this.allClienti
        .filter(m => !this.selectedClienti.some(sel => sel.id === m.id))
        .filter(m => m.nume.toLowerCase().includes(lowerQ));
    },
  },
  mounted() {
    this.selectedClienti = [...this.existingClienti];
  },
  methods: {
    onSearch() {
      this.showAutocomplete = !!this.searchQuery;
    },
    addMembru(client) {
        // Ensure the member has an observatii property for inline editing
        if (typeof client.observatii === 'undefined') {
            client.observatii = '';
        }

        this.selectedClienti.push(client);
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
      this.selectedClienti.splice(index, 1);
    },
    hideAutocomplete() {
      this.showAutocomplete = false;
    },
  },
};
</script>
