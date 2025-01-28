<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Add Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 5px 0;
        }

        .child-menu {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Manage Menus</h3>

        <!-- Add Menu Form -->
        <form action="add-menu" id="add-menu-form" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="menu-name" class="form-label">Menu Name</label>
                <input type="text" id="menu-name" name="name" class="form-control" placeholder="Enter menu name"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Add Menu</button>
        </form>
        <ul id="menu-list" class="list-group">
            @foreach ($menus as $item)
                <li class="list-group-item" data-id="{{ $item->id }}" draggable="true">
                    {{ $item->name }}
                    @if ($item->children->count() > 0)
                        <ul class="list-group nested">
                            @foreach ($item->children as $child)
                                <li class="list-group-item" data-id="{{ $child->id }}" draggable="true">
                                    {{ $child->name }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="list-group nested"></ul> <!-- Empty <ul> for future drops -->
                    @endif
                </li>
            @endforeach
        </ul>
    </div>



    <style>
        .nested {
            margin-left: 20px;
            padding-left: 20px;
            border-left: 1px solid #ccc;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
 
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const menuList = document.getElementById("menu-list");

    // Initialize Sortable for the root menu list
    Sortable.create(menuList, {
        group: "nested",
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        handle: ".list-group-item",
        onEnd: function (evt) {
            const draggedItem = evt.item; // The dragged list item
            let parentId = null;

            // Check if the dragged item is dropped in the root list
            const closestParent = draggedItem.closest("ul").closest("li"); // Find the closest parent <li> (if any)

            // If no parent <li> is found, it means it's dropped in the root menu (parentId = null)
            if (!closestParent || closestParent === menuList) {
                parentId = null;
                // Call the function to reset parent to null in the database
                resetParentToNull(draggedItem.dataset.id);
            } else {
                // If the item is nested under another item, update its parent ID
                parentId = closestParent.dataset.id;
                // Call the updateParentMenu function to update the database
                updateParentMenu(draggedItem.dataset.id, parentId);
            }

            console.log("Dragged Menu ID:", draggedItem.dataset.id);
            console.log("New Parent ID:", parentId);
        },
    });

    // Initialize Sortable for all nested lists dynamically
    document.querySelectorAll(".nested").forEach((nestedList) => {
        Sortable.create(nestedList, {
            group: "nested",
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            handle: ".list-group-item",
            onEnd: function (evt) {
                const draggedItem = evt.item;
                const closestParent = draggedItem.closest("ul").closest("li");
                let parentId = null;

                // If the item is dropped at the root, reset its parent to null
                if (!closestParent || closestParent === menuList) {
                    parentId = null;
                    resetParentToNull(draggedItem.dataset.id);
                } else {
                    parentId = closestParent.dataset.id;
                    updateParentMenu(draggedItem.dataset.id, parentId);
                }

                console.log("Dragged Menu ID:", draggedItem.dataset.id);
                console.log("New Parent ID:", parentId);
            }
        });
    });

    // Function to update the parent-child relationship in the database
    function updateParentMenu(menuId, parentId) {
        fetch("{{ route('update-menu-parent') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ menu_id: menuId, parent_id: parentId }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Menu updated successfully!");
            } else {
                alert("Failed to update the menu parent.");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred while updating the menu.");
        });
    }

    // Function to reset the parent ID to null in the database when the item is moved back to the root
    function resetParentToNull(menuId) {
      fetch("{{ route('reset-menu-parent') }}", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
    },
    body: JSON.stringify({ menu_id: menuId }),
})
.then((response) => response.json())
.then((data) => {
    if (data.success) {
        alert("Menu parent reset to root successfully!");
    } else {
        alert("Failed to reset the menu parent.");
    }
})
.catch((error) => {
    console.error("Error:", error);
    alert("An error occurred while resetting the menu parent.");
});
    }
});

</script>


</body>

</html>
