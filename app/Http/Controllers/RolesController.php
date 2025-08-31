<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{

//     public function addPermission(Request $request)
//     {
//         $permissions=[
//               'creer_demandes',
//               'voir_doctorants',
//               'voir_membres',
//               'creer_avis_soutenance',
//               'voir_resultats',
//               'creer_diplome',
//               'commission_theses',
//               'designer_rapporteurs',
//               'voir_rapporteurs',
//               'voir_membres_directeur',
//               'creer_nouvel_utilisateur',
//               'gestion_utilisateurs',
//         ];
//         foreach($permissions as $permission){
//         Permission::create(['name' => $permission]);}
//     }
// public function create(Request $request){

//     $role = Role::create(['name'=>$request->name]);
//     foreach($request->permission as $permission){
//         $role->givePermissionTo($permission);
//     }
//     foreach ($request->users as $user) {
//        $user=User::find($user);
//        $user->assignRole($role->name);
//     };
// }





public function givePermissionsAndAssignRoles(Request $request)
{
    // Définition des permissions pour chaque type de rôle
    $rolePermissions = [
        'service_ced' => [
            'creer_demandes',
            'voir_doctorants',
            'voir_membres',
            'creer_avis_soutenance',
            'voir_resultats',
            'creer_diplome',
        ],
        'directeur' => [
            'commission_theses',
            'designer_rapporteurs',
            'voir_rapporteurs',
            'voir_membres_directeur',
        ],
        'admin' => [
            'creer_demandes',
            'voir_doctorants',
            'voir_membres',
            'creer_avis_soutenance',
            'voir_resultats',
            'creer_diplome',
            'commission_theses',
            'designer_rapporteurs',
            'voir_rapporteurs',
            'voir_membres_directeur',
            'creer_nouvel_utilisateur',
            'gestion_utilisateurs',
        ],
    ];

    // Création des permissions et attribution aux rôles correspondants
    foreach ($rolePermissions as $roleName => $permissions) {
        // Generate a unique identifier for the role type
        $roleType = Str::slug($roleName, '_');

        // Check if a role with the same type exists
        if (Role::where('type', $roleType)->exists()) {
            // If exists, generate a unique role type
            $roleType = $roleType . '_' . Str::random(5); // Appending a random string
        }

        $role = Role::create(['name' => $roleName, 'type' => $roleType]);

        // Assign permissions to the role
        foreach ($permissions as $permissionName) {
            // Check if the permission already exists
            $existingPermission = DB::table('permissions')->where('name', $permissionName)->first();
            
            // If the permission doesn't exist, create it
            if (!$existingPermission) {
                DB::table('permissions')->insert(['name' => $permissionName]);
                $existingPermissionId = DB::getPdo()->lastInsertId(); // Get the ID of the newly inserted permission
            } else {
                $existingPermissionId = $existingPermission->id;
            }
            
            // Directly assign the permission to the role
            $role->permissions()->attach($existingPermissionId);
        }

        // Attribution du rôle aux utilisateurs du même type de rôle
        $usersWithType = User::where('type', $roleType)->get();
        foreach ($usersWithType as $user) {
            $user->assignRole($role->name);
        }
    }
}




}