import { Families, Family } from "../types/type";
import { Literal, MValue } from "utils";

/**
 * Улучшенная версия getFontFamily с более точным сопоставлением
 * 
 * Проблема оригинальной версии:
 * - "Oswald, 'Oswald Light', sans-serif" -> нормализуется в "oswald_oswald_light_sans_serif"
 * - Если не находит, берет только первую часть "oswald"
 * - Может найти неправильный шрифт (например, oswald_regular вместо oswald_light)
 * 
 * Улучшение:
 * - Сначала ищет полное совпадение
 * - Потом ищет по всем частям font-family (oswald, oswald_light)
 * - Только в конце использует первую часть как fallback
 */
export const getFontFamily = (
  styles: Record<string, Literal>,
  families: Families
): MValue<Family> => {
  const value = `${styles["font-family"]}`;

  // Полная нормализация (как в оригинале)
  const fontFamily = value
    .replace(/['"\,]/g, "") // eslint-disable-line
    .replace(/\s/g, "_")
    .toLocaleLowerCase();

  // 1. Сначала пытаемся найти полное совпадение
  if (families[fontFamily]) {
    return families[fontFamily];
  }

  // 2. Извлекаем все части font-family (без generic families)
  const genericFamilies = ['sans-serif', 'serif', 'monospace', 'cursive', 'fantasy'];
  const parts = value.split(',').map(part => {
    // Удаляем кавычки и пробелы, нормализуем
    let normalized = part.trim()
      .replace(/['"]/g, '')
      .replace(/\s/g, '_')
      .toLocaleLowerCase();
    return normalized;
  }).filter(part => {
    // Фильтруем generic families
    return part && !genericFamilies.includes(part);
  });

  // 3. Ищем по каждой части (начиная с более специфичных)
  // Например, для "Oswald, 'Oswald Light', sans-serif":
  // - Сначала ищем "oswald_light" (более специфичный)
  // - Потом "oswald" (менее специфичный)
  for (let i = parts.length - 1; i >= 0; i--) {
    const partKey = parts[i];
    if (families[partKey]) {
      return families[partKey];
    }
  }

  // 4. Fallback: используем первую часть (как в оригинале)
  const [firstFontFamily] = fontFamily.split("_");
  return families[firstFontFamily];
};
