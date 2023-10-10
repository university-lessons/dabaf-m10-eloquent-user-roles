# User - Roles Example

In this lesson:

- User and Roles Many to Many relationship
- Seeder for Roles
- Method "isTeacher" on User Model

To test in artisan:
- create an user: `$user = User::factory()->create()`
- get a role: `$role = Role::where('name', 'Teacher')->get()`
- attach a role to the user: `$user->roles()->attach($role)`
- inspect the relation: `User::first()->roles`
- inspect using the model method: `User::first()->isTeacher()`