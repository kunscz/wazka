<?php

namespace App\Modules\Core\Services;

use Illuminate\Support\Facades\Auth;

class ContextEvaluator
{
   public function isAllowed(string $permission): bool
   {
      $user = Auth::user();

      $isWorkHours = now()->isBetween('09:00', '17:00');
      $isTrustedDevice = request()->header('X-Trusted-Device') === 'true';

      return $user->can($permission) && $isWorkHours && $isTrustedDevice;
   }

   /**
    * Check if the current user has permission to access a specific context.
    *
    * @param string $context
    * @return bool
    */
   public function canAccessContext(string $context): bool
   {
      // Assuming the context is a permission name
      return Auth::user()->can($context);
   }

   /**
    * Get the current user's context permissions.
    *
    * @return array
    */
   public function getUserContextPermissions(): array
   {
      return Auth::user()->getAllPermissions()->pluck('name')->toArray();
   }
}