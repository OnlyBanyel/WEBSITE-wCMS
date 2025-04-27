<?php
require_once __DIR__ . "/../classes/element_styler.class.php";

// Initialize the ElementStyler
$styler = new ElementStyler();

// Get all available style options
$styleOptions = $styler->getStyleOptions();
?>

<div id="style-sidebar" class="style-sidebar hidden fixed right-0 top-0 h-screen w-80 bg-white shadow-lg z-50 overflow-y-auto transition-transform transform translate-x-full">
    <div class="p-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-primary">Style Editor</h2>
            <button id="close-style-sidebar" class="text-gray-500 hover:text-primary focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    
    <div class="p-4">
        <div class="mb-4">
            <p class="text-sm text-gray-500 mb-2">Currently editing: <span id="current-element-name" class="font-semibold">None selected</span></p>
            <input type="hidden" id="current-element-type" value="">
            <input type="hidden" id="current-element-id" value="">
        </div>
        
        <div class="space-y-4">
            <?php if (isset($styleOptions['font'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Font Family</h3>
                <select id="style-font" class="w-full p-2 border border-gray-300 rounded">
                    <option value="">Default</option>
                    <?php foreach ($styleOptions['font'] as $font): ?>
                    <option value="<?php echo htmlspecialchars($font['className']); ?>"><?php echo htmlspecialchars(str_replace('font-[', '', str_replace(']', '', $font['className']))); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['text-size'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Text Size</h3>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach ($styleOptions['text-size'] as $size): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="text-size" data-value="<?php echo htmlspecialchars($size['className']); ?>">
                        <span class="<?php echo htmlspecialchars($size['className']); ?>">Aa</span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['text-weight'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Text Weight</h3>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach ($styleOptions['text-weight'] as $weight): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="text-weight" data-value="<?php echo htmlspecialchars($weight['className']); ?>">
                        <span class="<?php echo htmlspecialchars($weight['className']); ?>">Aa</span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['text-color'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Text Color</h3>
                <div class="grid grid-cols-4 gap-2">
                    <?php foreach ($styleOptions['text-color'] as $color): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="text-color" data-value="<?php echo htmlspecialchars($color['className']); ?>">
                        <div class="w-full h-6 <?php echo htmlspecialchars($color['className']); ?> bg-current"></div>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['bg-color'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Background Color</h3>
                <div class="grid grid-cols-4 gap-2">
                    <?php foreach ($styleOptions['bg-color'] as $color): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="bg-color" data-value="<?php echo htmlspecialchars($color['className']); ?>">
                        <div class="w-full h-6 <?php echo htmlspecialchars($color['className']); ?>"></div>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['padding'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Padding</h3>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach ($styleOptions['padding'] as $padding): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="padding" data-value="<?php echo htmlspecialchars($padding['className']); ?>">
                        <?php echo htmlspecialchars($padding['className']); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['margin'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Margin</h3>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach ($styleOptions['margin'] as $margin): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="margin" data-value="<?php echo htmlspecialchars($margin['className']); ?>">
                        <?php echo htmlspecialchars($margin['className']); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['border'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Border</h3>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach ($styleOptions['border'] as $border): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="border" data-value="<?php echo htmlspecialchars($border['className']); ?>">
                        <div class="<?php echo htmlspecialchars($border['className']); ?> border-gray-500 h-6 w-full"></div>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['border-color'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Border Color</h3>
                <div class="grid grid-cols-4 gap-2">
                    <?php foreach ($styleOptions['border-color'] as $color): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="border-color" data-value="<?php echo htmlspecialchars($color['className']); ?>">
                        <div class="border-2 <?php echo htmlspecialchars($color['className']); ?> h-6 w-full"></div>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($styleOptions['border-radius'])): ?>
            <div class="style-category">
                <h3 class="text-md font-semibold mb-2">Border Radius</h3>
                <div class="grid grid-cols-3 gap-2">
                    <?php foreach ($styleOptions['border-radius'] as $radius): ?>
                    <button class="style-option p-2 border border-gray-300 rounded hover:bg-gray-100" data-category="border-radius" data-value="<?php echo htmlspecialchars($radius['className']); ?>">
                        <div class="bg-gray-300 h-6 w-full <?php echo htmlspecialchars($radius['className']); ?>"></div>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-6">
            <button id="reset-styles" class="w-full p-2 bg-red-500 text-white rounded hover:bg-red-600">
                Reset All Styles
            </button>
        </div>
    </div>
</div>

<button id="open-style-sidebar" class="fixed right-0 top-1/2 transform -translate-y-1/2 bg-primary text-white p-2 rounded-l-md shadow-lg z-40">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
</button>
