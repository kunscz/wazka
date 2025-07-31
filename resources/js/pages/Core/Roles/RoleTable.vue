<Table class="rounded border text-sm">
  <thead class="bg-muted text-muted-foreground">
    <tr>
      <th>Role</th>
      <th>Permissions</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="role in data" :key="role.id" class="hover:bg-muted/40">
      <td>{{ role.name }}</td>
      <td>{{ role.permissions.map(p => p.label).join(', ') }}</td>
      <td>
        <Button variant="ghost" @click="$emit('edit', role)">Edit</Button>
      </td>
    </tr>
  </tbody>
</Table>