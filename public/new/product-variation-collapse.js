/**
 * Product Variation Options Collapsible Script
 * This script adds collapsible functionality to variation options in the product edit form
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all variation options as collapsed except the first one in each variation
    initializeCollapsibleOptions();
    
    // Add event listener for new variations added dynamically
    document.getElementById('variationsContainer').addEventListener('DOMNodeInserted', function(event) {
        if (event.target.classList && event.target.classList.contains('variation')) {
            initializeCollapsibleOptionsForVariation(event.target);
        }
    });
    
    // Function to initialize all existing variation options
    function initializeCollapsibleOptions() {
        const variations = document.querySelectorAll('.variation');
        variations.forEach(function(variation) {
            initializeCollapsibleOptionsForVariation(variation);
        });
    }
    
    // Function to initialize options for a specific variation
    function initializeCollapsibleOptionsForVariation(variation) {
        const options = variation.querySelectorAll('.option');
        
        options.forEach(function(option, index) {
            // Create the collapsible structure
            makeOptionCollapsible(option, index === 0);
        });
        
        // Add event listener for new options added dynamically
        variation.querySelector('.options-container').addEventListener('DOMNodeInserted', function(event) {
            if (event.target.classList && event.target.classList.contains('option')) {
                makeOptionCollapsible(event.target, false);
            }
        });
    }
    
    // Function to make an option collapsible
    function makeOptionCollapsible(option, initiallyExpanded) {
        // Get the option header (first row with option name and remove button)
        const optionHeader = option.querySelector('.d-flex.justify-content-between.align-items-center');
        
        // Get all content to be collapsed (everything except the header)
        const contentRows = Array.from(option.children).filter(child => child !== optionHeader);
        
        // Create a container for the collapsible content
        const contentContainer = document.createElement('div');
        contentContainer.className = 'option-content-container';
        contentContainer.style.overflow = 'hidden';
        contentContainer.style.transition = 'max-height 0.3s ease-out';
        
        // Move all content rows into the container
        contentRows.forEach(row => {
            option.removeChild(row);
            contentContainer.appendChild(row);
        });
        
        // Add the container after the header
        option.appendChild(contentContainer);
        
        // Create and add a toggle button/icon to the header
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'btn btn-sm btn-outline-secondary ms-2';
        toggleButton.innerHTML = initiallyExpanded ? '▼' : '►';
        toggleButton.style.marginRight = '10px';
        toggleButton.title = initiallyExpanded ? 'Collapse' : 'Expand';
        
        // Insert the toggle button before the remove button
        const removeButton = optionHeader.querySelector('.remove-option');
        optionHeader.insertBefore(toggleButton, removeButton);
        
        // Get the option name for display in the header
        const optionName = option.querySelector('input[name$="[name]"]');
        
        // Create a span to display the option name in the header
        const optionNameDisplay = document.createElement('span');
        optionNameDisplay.className = 'option-name-display';
        optionNameDisplay.textContent = optionName ? optionName.value : 'Option';
        
        // Update the option name display when the input changes
        if (optionName) {
            optionName.addEventListener('input', function() {
                optionNameDisplay.textContent = this.value || 'Option';
            });
        }
        
        // Replace the "Option" text with our dynamic display
        const headerTitle = optionHeader.querySelector('h6');
        headerTitle.innerHTML = '';
        headerTitle.appendChild(optionNameDisplay);
        
        // Set initial state
        if (initiallyExpanded) {
            contentContainer.style.maxHeight = contentContainer.scrollHeight + 'px';
        } else {
            contentContainer.style.maxHeight = '0px';
        }
        
        // Add click event to toggle visibility
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (contentContainer.style.maxHeight === '0px') {
                // Expand
                contentContainer.style.maxHeight = contentContainer.scrollHeight + 'px';
                toggleButton.innerHTML = '▼';
                toggleButton.title = 'Collapse';
            } else {
                // Collapse
                contentContainer.style.maxHeight = '0px';
                toggleButton.innerHTML = '►';
                toggleButton.title = 'Expand';
            }
        });
        
        // Make the entire header clickable to toggle
        optionHeader.style.cursor = 'pointer';
        optionHeader.addEventListener('click', function(e) {
            // Don't trigger if clicking on the remove button
            if (e.target !== removeButton && !removeButton.contains(e.target)) {
                toggleButton.click();
            }
        });
    }
});