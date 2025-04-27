<div id="style-panel" class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
  <div class="p-4 border-b border-gray-200">
      <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-800">Style Editor</h3>
          <button id="close-style-panel" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
          </button>
      </div>
      <div class="mt-2">
          <p class="text-sm text-gray-600">Editing: <span id="current-element-name" class="font-medium">None selected</span></p>
          <input type="hidden" id="current-section-id" value="">
      </div>
  </div>
  
  <div class="p-4 space-y-6">
      <!-- Font Family -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Font Family</label>
          <select id="style-font" class="w-full border border-gray-300 rounded-md p-2 text-sm">
              <option value="">Default</option>
              <option value="font-[Inter]">Inter</option>
              <option value="font-[Roboto]">Roboto</option>
              <option value="font-[Poppins]">Poppins</option>
              <option value="font-[Merriweather]">Merriweather</option>
              <option value="font-[Montserrat]">Montserrat</option>
              <option value="font-[OpenSans]">Open Sans</option>
              <option value="font-[Lato]">Lato</option>
              <option value="font-mono">Monospace</option>
              <option value="font-serif">Serif</option>
          </select>
      </div>
      
      <!-- Text Size -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Text Size</label>
          <div class="grid grid-cols-4 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-xs">XS</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-sm">SM</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-base">Base</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-lg">LG</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-xl">XL</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-2xl">2XL</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-3xl">3XL</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-size" data-value="text-4xl">4XL</button>
          </div>
      </div>
      
      <!-- Text Weight -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Text Weight</label>
          <div class="grid grid-cols-3 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-weight" data-value="font-light">Light</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-weight" data-value="font-normal">Normal</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-weight" data-value="font-medium">Medium</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-weight" data-value="font-semibold">Semibold</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-weight" data-value="font-bold">Bold</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="text-weight" data-value="font-extrabold">Extra Bold</button>
          </div>
      </div>
      
      <!-- Text Color -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
          <div class="grid grid-cols-4 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-gray-800 text-white hover:bg-gray-700" data-category="text-color" data-value="text-gray-800">Black</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-gray-500 text-white hover:bg-gray-400" data-category="text-color" data-value="text-gray-500">Gray</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-white text-gray-800 hover:bg-gray-50" data-category="text-color" data-value="text-white">White</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-red-600 text-white hover:bg-red-500" data-category="text-color" data-value="text-red-600">Red</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-blue-600 text-white hover:bg-blue-500" data-category="text-color" data-value="text-blue-600">Blue</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-green-600 text-white hover:bg-green-500" data-category="text-color" data-value="text-green-600">Green</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-yellow-500 text-white hover:bg-yellow-400" data-category="text-color" data-value="text-yellow-500">Yellow</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-purple-600 text-white hover:bg-purple-500" data-category="text-color" data-value="text-purple-600">Purple</button>
          </div>
      </div>
      
      <!-- Background Color -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
          <div class="grid grid-cols-4 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-transparent hover:bg-gray-50" data-category="bg-color" data-value="">None</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-gray-100 hover:bg-gray-200" data-category="bg-color" data-value="bg-gray-100">Light</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-gray-200 hover:bg-gray-300" data-category="bg-color" data-value="bg-gray-200">Gray</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-red-100 hover:bg-red-200" data-category="bg-color" data-value="bg-red-100">Red</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-blue-100 hover:bg-blue-200" data-category="bg-color" data-value="bg-blue-100">Blue</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-green-100 hover:bg-green-200" data-category="bg-color" data-value="bg-green-100">Green</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-yellow-100 hover:bg-yellow-200" data-category="bg-color" data-value="bg-yellow-100">Yellow</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center bg-purple-100 hover:bg-purple-200" data-category="bg-color" data-value="bg-purple-100">Purple</button>
          </div>
      </div>
      
      <!-- Padding -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Padding</label>
          <div class="grid grid-cols-4 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="padding" data-value="p-0">None</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="padding" data-value="p-1">XS</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="padding" data-value="p-2">SM</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="padding" data-value="p-4">MD</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="padding" data-value="p-6">LG</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="padding" data-value="p-8">XL</button>
          </div>
      </div>
      
      <!-- Border -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Border</label>
          <div class="grid grid-cols-3 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border" data-value="border-0">None</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border" data-value="border">Thin</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border" data-value="border-2">Medium</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border" data-value="border-4">Thick</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border" data-value="border-b">Bottom</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border" data-value="border-t">Top</button>
          </div>
      </div>
      
      <!-- Border Color -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Border Color</label>
          <div class="grid grid-cols-4 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-gray-300">Gray</button>
              <button class="style-option p-2 border border-red-500 rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-red-500">Red</button>
              <button class="style-option p-2 border border-blue-500 rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-blue-500">Blue</button>
              <button class="style-option p-2 border border-green-500 rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-green-500">Green</button>
              <button class="style-option p-2 border border-yellow-500 rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-yellow-500">Yellow</button>
              <button class="style-option p-2 border border-purple-500 rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-purple-500">Purple</button>
              <button class="style-option p-2 border border-black rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-black">Black</button>
              <button class="style-option p-2 border border-white bg-gray-100 rounded-md text-center hover:bg-gray-50" data-category="border-color" data-value="border-white">White</button>
          </div>
      </div>
      
      <!-- Border Radius -->
      <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Border Radius</label>
          <div class="grid grid-cols-3 gap-2">
              <button class="style-option p-2 border border-gray-300 rounded-none text-center hover:bg-gray-50" data-category="border-radius" data-value="rounded-none">None</button>
              <button class="style-option p-2 border border-gray-300 rounded-sm text-center hover:bg-gray-50" data-category="border-radius" data-value="rounded-sm">Small</button>
              <button class="style-option p-2 border border-gray-300 rounded-md text-center hover:bg-gray-50" data-category="border-radius" data-value="rounded-md">Medium</button>
              <button class="style-option p-2 border border-gray-300 rounded-lg text-center hover:bg-gray-50" data-category="border-radius" data-value="rounded-lg">Large</button>
              <button class="style-option p-2 border border-gray-300 rounded-xl text-center hover:bg-gray-50" data-category="border-radius" data-value="rounded-xl">Extra Large</button>
              <button class="style-option p-2 border border-gray-300 rounded-full text-center hover:bg-gray-50" data-category="border-radius" data-value="rounded-full">Full</button>
          </div>
      </div>
      
      <!-- Reset Button -->
      <div class="pt-4 border-t border-gray-200">
          <button id="reset-styles" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
              Reset All Styles
          </button>
      </div>
  </div>
</div>
