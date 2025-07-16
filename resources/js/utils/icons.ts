/**
 * resolve icons from icon name string provided when create menu
 */
import type { LucideIcon } from "lucide-vue-next";

const iconCache = new Map<string, LucideIcon>();
type LucideModule = Record<string, LucideIcon>

export async function resolveIcon(name: string): Promise<LucideIcon> {
   if (iconCache.has(name)) return iconCache.get(name)!;

   try {
      const mod = await import('lucide-vue-next') as unknown as LucideModule
      const icon = mod[name] ?? mod.Folder;

      iconCache.set(name, icon)

      if (!mod[name]) {
         console.warn('Icon "${name}" not found in lucide-vue-next. Using fallback.')
      }

      return icon;

   } catch {
      const fallback = (await import('lucide-vue-next')).Folder;
      iconCache.set(name, fallback);
      return fallback;
   }
}