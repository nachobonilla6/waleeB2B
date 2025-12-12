<div class="w-full">
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
        <div 
            class="bg-primary-600 h-2.5 rounded-full transition-all duration-300"
            style="width: {{ $getRecord()->progress ?? 0 }}%"
        ></div>
    </div>
</div>
