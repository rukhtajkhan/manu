<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Menu;
use Illuminate\Http\Request;

class ManuController extends Controller
{
    public function addMenu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new menu item
        $menu = Menu::create([
            'name' => $request->input('name'),
            'parent_id' => null, // Default to no parent
        ]);

       return redirect()->back()->with('success','menu add seccussfully');
    }
    public function updateParent(Request $request)
    {
        // Get menu and parent IDs from the request
        $menuId = $request->input('menu_id');
        $parentId = $request->input('parent_id');
    
        // Validate menu ID
        if (is_null($menuId)) {
            return response()->json(['success' => false, 'message' => 'Invalid menu ID.']);
        }
    
        // Find the menu item by ID
        $menu = Menu::find($menuId);
    
        // Check if menu exists
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu not found.']);
        }
    
        // If parent ID is null, ensure it's set as root (null parent ID)
        $menu->parent_id = $parentId === null ? null : $parentId;
    
        try {
            // Save the updated menu to the database
            $menu->save();
    
            // Return success response
            return response()->json(['success' => true, 'message' => 'Menu updated successfully!']);
        } catch (\Exception $e) {
            // Log error if an exception occurs
            \Log::error('Menu update error: ' . $e->getMessage());
    
            // Return failure response
            return response()->json(['success' => false, 'message' => 'Failed to update the menu parent.']);
        }
    }
    
    

    
public function getMenuList()
{
    try {
        $menus = Menu::with('children')->get();  // Fetch menus with their children
        return response()->json([
            'success' => true,
            'menus' => $menus,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching menu list: ' . $e->getMessage(),
        ]);
    }
}
public function resetParentToNull(Request $request)
{
   
    $menuId = $request->input('menu_id');
    
    // Update the menu item in the database to reset the parent_id to null
    $menu = Menu::find($menuId);
    if ($menu) {
        $menu->parent_id = null;
        $menu->save();
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 400);
}

    
    

    
}
