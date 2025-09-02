function generateToast(text, icon = "warning", heading = "") {
  $.toast({
    text: text, // Text that is to be shown in the toast
    icon: icon, // Type of toast icon
    heading: heading,
    showHideTransition: "fade", // fade, slide or plain
    allowToastClose: true, // Boolean value true or false
    hideAfter: 3000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
    position: "top-right", // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
  });
}

function resetForm() {
  const passwordStrength = $("#passwordStrength");
  if(passwordStrength) {
    passwordStrength.css("width", '')
  }
  $("form")[0].reset();
}

function getBadgeClass(status) {
  if (status === "pending") {
    return "info";
  } else if (status === "approved") {
    return "success";
  } else {
    return "danger";
  }
}

function capitalizeFirstLetter(str) {
  if (typeof str !== "string" || str.length === 0) {
    return str; // Handle non-string or empty inputs
  }
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function initDataTable(fetch_endpoint, customColumns, hasStatus = false) {
  let columns = resolveTableColumns(fetch_endpoint, customColumns, hasStatus);

  return $(`.dataTables-ajax`).DataTable({
    ajax: {
      url: `${fetch_endpoint}`, // Your PHP endpoint that returns JSON
      dataSrc: "", // If your data is a direct array
    },
    columns: columns,
    pageLength: 10,
    responsive: true,
    order: [[0, "asc"]],
    dom: '<"row mt-3 mb-3"<"col-sm-12 col-md-7 d-flex justify-content-between align-items-center"lB><"col-sm-12 col-md-5"f>>rt<"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    language: {
      emptyTable: `
            <div class="text-center text-muted py-4">
                <i class="fa fa-info-circle fa-2x mb-2 text-warning"></i><br>
                <strong>No data found</strong><br>
                <small>There are currently no records to display.</small>
            </div>
        `,
      zeroRecords: `
            <div class="text-center text-muted py-4">
                <i class="fa fa-info-circle fa-2x mb-2 text-warning"></i><br>
                <strong>No records match your search</strong><br>
                <small>Try adjusting your search criteria.</small>
            </div>
        `,
      search: "",
      searchPlaceholder: "Search...",
      lengthMenu: `Show _MENU_ entries`,
      info: `Showing _START_ to _END_ of _TOTAL_ entries`,
      paginate: {
        first: '<i class="fa fa-angle-double-left"></i>',
        last: '<i class="fa fa-angle-double-right"></i>',
        next: '<i class="fa fa-angle-right"></i>',
        previous: '<i class="fa fa-angle-left"></i>',
      },
    },
    columnDefs: [
      { targets: "no-sort", orderable: false },
      { targets: [4, 5], className: "text-center" },
    ],
    buttons: [
      {
        extend: "copy",
        className: "btn btn-sm btn-outline-primary mr-1",
        text: '<i class="fa fa-copy"></i> Copy',
      },
      {
        extend: "csv",
        className: "btn btn-sm btn-outline-primary mr-1",
        text: '<i class="fa fa-file-csv"></i> CSV',
      },
      {
        extend: "excel",
        className: "btn btn-sm btn-outline-primary mr-1",
        text: '<i class="fa fa-file-excel"></i> Excel',
        title: "Instructors_Export",
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-outline-primary mr-1",
        text: '<i class="fa fa-file-pdf"></i> PDF',
        title: "Instructors Directory",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 6],
        },
      },
      {
        extend: "print",
        className: "btn btn-sm btn-outline-primary",
        text: '<i class="fa fa-print"></i> Print',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5],
        },
        customize: function (win) {
          $(win.document.body).addClass("white-bg");
          $(win.document.body).css("font-size", "12px");
          $(win.document.body)
            .find("table")
            .addClass("compact")
            .css("font-size", "inherit");
        },
      },
    ],
    // ... rest of your configuration
  });
}

function resolveTableColumns(fetch_endpoint, customColumns, hasStatus = true) {
  const columns = [
    {
      data: "id",
      render: function (data) {
        return data.toString().padStart(4, "0");
      },
    },
    ...customColumns,
  ];

  // Conditionally add status column
  if (hasStatus) {
    columns.push({
      data: "status",
      render: function (data) {
        return `<div class="col-sm-8">
                  <span class="badge badge-${getBadgeClass(
                    data
                  )} badge-pill px-3 py-2" style="font-size: 0.85em;">
                    ${capitalizeFirstLetter(data)}
                  </span>
                </div>`;
      },
    });
  }

  // Add remaining columns
  columns.push(
    {
      data: "created_at",
      render: function (data) {
        const joinDate = new Date(data);
        const formattedDate = joinDate.toLocaleDateString("en-US", {
          year: "numeric",
          month: "short",
          day: "2-digit",
        });
        const monthsAgo = Math.round(
          (Date.now() - joinDate.getTime()) / (30 * 24 * 60 * 60 * 1000)
        );
        return `${formattedDate}<br><small class="text-muted">${monthsAgo} months ago</small>`;
      },
    },
    {
      data: "id",
      render: function (data) {
        return `<div class="btn-group btn-group-sm">
                  <a href="${fetch_endpoint}/show/${data}" class="btn btn-outline-primary">
                    <i class="fa fa-eye"></i>
                  </a>
                  <a href="${fetch_endpoint}/update/${data}" class="btn btn-outline-warning ml-1">
                    <i class="fa fa-edit"></i>
                  </a>
                  <button class="deleteBtn btn btn-outline-danger ml-1" data-key="${data}">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>`;
      },
    }
  );

  return columns;
}

// Toast helper
const showToast = (text, icon, title) => {
  generateToast(text, icon, title);
};

async function deleteEntity(entityId, CONFIG, isTable = true) {
  if (!confirm(CONFIG.MESSAGES.DELETE_CONFIRM)) return;
  console.log("DELETE endpoint:", CONFIG.ENDPOINTS.DELETE);
  try {
    // * await pauses the function until the Promise is settled
    const response = await fetch(CONFIG.ENDPOINTS.DELETE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ table_id: entityId }),
    });

    const result = await response.json();
    if (response.ok) {
      console.log(result);
      showToast(CONFIG.MESSAGES.DELETE_SUCCESS, "success", "Success");
      if (isTable) {
        table.ajax.reload();
      }
    } else {
      console.log(result);
      throw new Error(result.error || "Delete failed");
    }
  } catch (error) {
    console.error("Delete error:", error.message);
    showToast(error.message, "error", "ERROR");
  }
}
