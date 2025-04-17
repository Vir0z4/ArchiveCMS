function createLinkEntry() {
    const entry = document.createElement('div');
    entry.className = 'link-entry';
    entry.innerHTML = `
        <input type="text" placeholder="URL">
        <input type="text" placeholder="Text">
        <button type="button" class="remove-link-btn">Remove</button>
    `;
    return entry;
}

// Generic row creation function
function createTableRow(fields, hasLinks) {
    const row = document.createElement('tr');
    
    fields.forEach(field => {
        const td = document.createElement('td');
        const input = document.createElement('input');
        input.type = "text";
        td.appendChild(input);
        row.appendChild(td);
    });

    if (hasLinks) {
        const linksTd = document.createElement('td');
        linksTd.className = 'links-cell';
        linksTd.innerHTML = `
            <div class="links-container"></div>
            <button type="button" class="add-link-btn">Add Link</button>
        `;
        row.appendChild(linksTd);
    }

    const removeTd = document.createElement('td');
    const removeBtn = document.createElement('button');
    removeBtn.type = "button";
    removeBtn.className = "remove-row-btn";
    removeBtn.textContent = "Remove";
    removeTd.appendChild(removeBtn);
    row.appendChild(removeTd);

    return row;
}

document.addEventListener("DOMContentLoaded", () => {
    // Add Recovery Disc
    document.getElementById("add-recovery-disc").addEventListener("click", () => {
        const tableBody = document.querySelector("#recovery-discs-table tbody");
        const row = createTableRow(['model', 'windows'], true);
        tableBody.appendChild(row);
    });

    // Add Driver Pack
    document.getElementById("add-driver-pack").addEventListener("click", () => {
        const tableBody = document.querySelector("#driver-packs-table tbody");
        const row = createTableRow(['model', 'description'], true);
        tableBody.appendChild(row);
    });

    // Add Driver
    document.getElementById("add-driver").addEventListener("click", () => {
        const tableBody = document.querySelector("#drivers-table tbody");
        const row = createTableRow(['type', 'description'], true);
        tableBody.appendChild(row);
    });

    // Add Broken Link
    document.getElementById("add-broken-link").addEventListener("click", () => {
        const tableBody = document.querySelector("#broken-links-table tbody");
        const row = createTableRow(['type', 'description', 'exe'], false);
        tableBody.appendChild(row);
    });

    // Handle dynamic elements
    document.addEventListener("click", (e) => {
        // Add links
        if (e.target.classList.contains("add-link-btn")) {
            const container = e.target.previousElementSibling;
            container.appendChild(createLinkEntry());
        }
        
        // Remove links
        if (e.target.classList.contains("remove-link-btn")) {
            e.target.closest(".link-entry").remove();
        }
        
        // Remove rows
        if (e.target.classList.contains("remove-row-btn")) {
            e.target.closest("tr").remove();
        }
    });

    // Form submission handler
    document.getElementById("page-form").addEventListener("submit", () => {
        // Recovery Discs
        const rdData = Array.from(document.querySelectorAll("#recovery-discs-table tbody tr")).map(row => ({
            model: row.children[0].querySelector("input").value.trim(),
            windows: row.children[1].querySelector("input").value.trim(),
            links: Array.from(row.querySelectorAll(".link-entry")).map(link => ({
                url: link.querySelector("input:nth-child(1)").value.trim(),
                text: link.querySelector("input:nth-child(2)").value.trim()
            }))
        }));
        document.getElementById("recovery_discs_json").value = JSON.stringify(rdData);

        // Driver Packs
        const dpData = Array.from(document.querySelectorAll("#driver-packs-table tbody tr")).map(row => ({
            model: row.children[0].querySelector("input").value.trim(),
            description: row.children[1].querySelector("input").value.trim(),
            links: Array.from(row.querySelectorAll(".link-entry")).map(link => ({
                url: link.querySelector("input:nth-child(1)").value.trim(),
                text: link.querySelector("input:nth-child(2)").value.trim()
            }))
        }));
        document.getElementById("driver_packs_json").value = JSON.stringify(dpData);

        // Drivers
        const drvData = Array.from(document.querySelectorAll("#drivers-table tbody tr")).map(row => ({
            type: row.children[0].querySelector("input").value.trim(),
            description: row.children[1].querySelector("input").value.trim(),
            links: Array.from(row.querySelectorAll(".link-entry")).map(link => ({
                url: link.querySelector("input:nth-child(1)").value.trim(),
                text: link.querySelector("input:nth-child(2)").value.trim()
            }))
        }));
        document.getElementById("drivers_json").value = JSON.stringify(drvData);

        // Broken Links
        const blData = Array.from(document.querySelectorAll("#broken-links-table tbody tr")).map(row => ({
            type: row.children[0].querySelector("input").value.trim(),
            description: row.children[1].querySelector("input").value.trim(),
            exe: row.children[2].querySelector("input").value.trim()
        }));
        document.getElementById("broken_links_json").value = JSON.stringify(blData);
    });
});