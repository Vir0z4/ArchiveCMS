function tableToArray(tableId, columns) {
const table = document.getElementById(tableId);
const rows = table.querySelectorAll("tbody tr");
const data = [];
rows.forEach((row) => {
    const cells = row.querySelectorAll("td input");
    const obj = {};
    columns.forEach((col, idx) => {
    obj[col] = cells[idx].value.trim();
    });
    data.push(obj);
});
return data;
}

function addRow(tableId, columns, defaultValues) {
const tableBody = document.querySelector(`#${tableId} tbody`);
const row = document.createElement("tr");
columns.forEach((col) => {
    const td = document.createElement("td");
    const input = document.createElement("input");
    input.type = "text";
    input.value = defaultValues[col] || "";
    td.appendChild(input);
    row.appendChild(td);
});

const removeTd = document.createElement("td");
const removeBtn = document.createElement("button");
removeBtn.type = "button";
removeBtn.textContent = "Remove";
removeBtn.classList.add("remove-row-btn");
removeTd.appendChild(removeBtn);
row.appendChild(removeTd);

tableBody.appendChild(row);
}

document.addEventListener("DOMContentLoaded", () => {
document.getElementById("add-recovery-disc").addEventListener("click", () => {
    addRow("recovery-discs-table", ["model", "windows", "link", "link_text"], {});
});
document.getElementById("add-driver-pack").addEventListener("click", () => {
    addRow("driver-packs-table", ["model", "description", "link", "link_text"], {});
});
document.getElementById("add-driver").addEventListener("click", () => {
    addRow("drivers-table", ["type", "description", "link", "link_text"], {});
});
document.getElementById("add-broken-link").addEventListener("click", () => {
    addRow("broken-links-table", ["type", "description", "exe"], {});
});

document.addEventListener("click", (e) => {
    if (e.target && e.target.classList.contains("remove-row-btn")) {
    e.target.closest("tr").remove();
    }
});

document.getElementById("page-form").addEventListener("submit", () => {
    const rdData = tableToArray("recovery-discs-table", ["model", "windows", "link", "link_text"]);
    document.getElementById("recovery_discs_json").value = JSON.stringify(rdData);

    const dpData = tableToArray("driver-packs-table", ["model", "description", "link", "link_text"]);
    document.getElementById("driver_packs_json").value = JSON.stringify(dpData);

    const drvData = tableToArray("drivers-table", ["type", "description", "link", "link_text"]);
    document.getElementById("drivers_json").value = JSON.stringify(drvData);

    const blData = tableToArray("broken-links-table", ["type", "description", "exe"]);
    document.getElementById("broken_links_json").value = JSON.stringify(blData);
});
});