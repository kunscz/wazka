import type { LucideIcon } from "lucide-vue-next";

export async function resolveIcon(name: string): Promise<LucideIcon> {
   try {
      const icon = await import(`lucide-vue-next/icons/${name}.js`);
      return icon.default;
   } catch (e) {
      return (await import('lucide-vue-next')).Folder;
   }
}