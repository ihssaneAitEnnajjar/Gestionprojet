<template>
    <div>
      <div class="md:block">
        <div class="relative flex items-center text-gray-400 focus-within:text-cyan-400">
          <span class="absolute left-4 h-6 flex items-center pr-3 border-r border-gray-300">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 fill-current"
              viewBox="0 0 35.997 36.004"
            >
              <!-- SVG path here -->
            </svg>
          </span>
          <input
            type="search"
            name="leadingIcon"
            id="leadingIcon"
            placeholder="Search here"
            class="w-full pl-14 pr-4 py-2.5 rounded-xl text-sm text-gray-600 outline-none border border-gray-300 focus:border-cyan-300 transition"
            v-model="searchTerm"
            @input="filterResults"
          />
        </div>
      </div>
      <button
        aria-label="search"
        class="w-10 h-10 rounded-xl border bg-gray-100 focus:bg-gray-100 active:bg-gray-200 md:hidden"
        @click="filterResults"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-4 mx-auto fill-current text-gray-600"
          viewBox="0 0 35.997 36.004"
        >
          <!-- SVG path here -->
        </svg>
      </button>
  
      <div id="searchResults">
        <div v-for="(result, index) in filteredResults" :key="index">
          {{ result }}
        </div>
      </div>
    </div>
  </template>
  <script>
  export default {
    data() {
      return {
        searchTerm: '',
        searchData: [
          'Apple',
          'Banana',
          'Orange',
          'Mango',
          'Grapes'
        ]
      };
    },
    computed: {
      filteredResults() {
        return this.searchData.filter(result =>
          result.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
      }
    },
    methods: {
      filterResults() {
        // This method is automatically triggered when input changes or button is clicked
        // Filtered results are handled by computed property
      }
    }
  };
  </script>
  