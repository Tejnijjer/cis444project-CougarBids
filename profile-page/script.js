// Sample data
const myItems = [
    { id: 1, name: "Math Textbook", price: "$30", desc: "Good condition" },
    { id: 2, name: "Desk Lamp", price: "$15", desc: "LED, barely used" }
];

const savedItems = [
    { id: 101, name: "Bike", price: "$80", desc: "Mountain bike" },
    { id: 102, name: "Mini Fridge", price: "$50", desc: "Works great" }
];

// Display items when page loads
window.onload = function() {
    displayItems(myItems, 'listed');
    displayItems(savedItems, 'liked');
};

function displayItems(items, tabId) {
    const container = document.querySelector(`#${tabId} .items`);
    container.innerHTML = '';
    
    items.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'item';
        itemDiv.innerHTML = `
            <h3>${item.name}</h3>
            <p>${item.price}</p>
            <p>${item.desc}</p>
        `;
        container.appendChild(itemDiv);
    });
}

function showTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabId).classList.add('active');
    
    // Update button styles
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    event.target.classList.add('active');
}

function addItem() {
    const name = prompt("Item name:");
    const price = prompt("Item price:");
    const desc = prompt("Item description:");
    
    if (name && price && desc) {
        myItems.push({
            id: Date.now(),
            name,
            price: "$" + price,
            desc
        });
        
        displayItems(myItems, 'listed');
    }
}

// Make dropdown work on mobile
document.querySelector('.profile-toggle').addEventListener('click', function(e) {
    // Only toggle on mobile (prevent conflict with hover)
    if (window.innerWidth <= 768) {
        e.preventDefault();
        const menu = this.nextElementSibling;
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
});

// Close dropdown when clicking elsewhere
document.addEventListener('click', function(e) {
    if (!e.target.closest('.profile-dropdown') && window.innerWidth <= 768) {
        document.querySelector('.dropdown-menu').style.display = 'none';
    }
});