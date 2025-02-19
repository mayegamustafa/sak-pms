<<<<<<< PMS
(Your local changes)
=======
(Changes from the remote repository)
>>>>>>> origin/main
creating roles:
#bash
php artisan tinker

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Super Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => User::ROLE_SUPER_ADMIN,
]);


User::create([
    'name' => 'Manager User',
    'email' => 'manager@example.com',
    'password' => Hash::make('password'),
    'role' => User::ROLE_MANAGER,
]);

User::create([
    'name' => 'Property Owner',
    'email' => 'owner@example.com',
    'password' => Hash::make('password'),
    'role' => User::ROLE_OWNER,
]);
