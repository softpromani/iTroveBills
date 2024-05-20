<template>
    <div v-if="isVisible" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <header class="flex justify-between items-center">
          <h2 class="text-xl font-semibold">Modal Title</h2>
          <button @click="closeModal" class="text-black hover:text-red-600">&times;</button>
        </header>
        <main class="mt-4">
          <p>This is a modal!</p>
        </main>
        <footer class="mt-4 flex justify-end">
          <button @click="closeModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Close
          </button>
        </footer>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['isVisible'],
    methods: {
      closeModal() {
        this.$emit('update:isVisible', false);
      }
    }
  }
  </script>
  