# Moduł Product Video Loops

Moduł przeznaczony dla PrestaShop 8, umożliwia wgrywanie i wyświetlanie krótkich zapętlonych filmów w miejscu standardowych zdjęć produktu. Zwiększa atrakcyjność i wzbogaca prezentację produktów, co może zwiększyć zaangażowanie klientów i poprawić konwersję w sklepie.

## Wymagania

- PrestaShop w wersji **8.x**
- Obsługiwany format pliku wideo MP4 – dozwolone rozszerzenia i rozmiary można edytować w kodzie modułu.
- PHP 8.0+

## Funkcjonalności

1. **Dodawanie wideo w edycji produktu**  
   - Moduł po zainstalowaniu dodaje nowe pole w formularzu edycji produktu (zakładka „Opis” → sekcja „Filmy produktowe”).  
   - Wideo zostaje zapisane w bazie danych w nowej tabeli i w katalogu `/img/videoloops/`.
   - Do każdego produktu można wgrać tylko jednen film.

2. **Ustawianie filmu jako okładka produktu**  
   - Po dodaniu **wideo automatycznie zamienia się w główną okładkę (cover)** danego produktu. Zmiany widoczne są na stronie produktowej, na stronie kategorii i w modalu szybki podgląd.

3. **Podmiana w warstwie Front Office**  
   - Do poprawnego wyświetlania filmu w Front Office PrestaShop należy ręcznie zmodyfikować pliki szablonu. Przykładowe pliki szablonu motywu `classic` znajdują się w folderze `Classic theme modification`. Należy je podmienić a pliki .js skompilować. Modyfikacja wstawia `<video>` zamiast `<img>` w miejscu głównego zdjęcia produktu.

4. **Podgląd i usuwanie filmu**  
   - Wgrany film można zoabczyć na stronie edycji produktu.
   - Ikona do usuwania filmu pojawia się kiedy do produktu wgrane jest wideo. 

## Instalacja

1. Zainstaluj moduł w Back Office (Dodatki → Moduły → Zainstaluj).
2. Podmień pliki szablonu `classic`
3. Podmień pliki JavaScript i uruchom kompilację, a następnie podmień plik theme.js
4. Po instalacji w menu **Produkty → Edycja produktu → Zakładka „Opis”** powinna pojawić się nowa sekcja do wgrywania wideo.

## Konfiguracja

- Dozwolone rozmiary i formaty znajdują się w pliku `VideoCompoundType.php` w sekcji `constraints`.
- Ścieżkę zapisu plików wideo zmienisz w `LinkBuilderService.php`.

## Uwagi i rozwój

- W przypadku niestandardowych motywów – konieczna może być dodatkowa integracja i dostosowanie plików `.tpl` (np. `product-cover-thumbnails.tpl`).

---

# Product Video Loops Module

A module designed for PrestaShop 8 that lets you upload and display short looping videos in place of the standard product images. It enhances the product presentation by making it more engaging, which may lead to increased customer interest and improved conversion in your store.

## Requirements

- PrestaShop **8.x**
- Supported video file format: MP4 – allowed extensions and file sizes can be adjusted in the module’s code.
- PHP 8.0+

## Features

1. **Adding a Video in the Product Edit Page**  
   - Once installed, the module adds a new field in the product editing form (tab “Description” → section “Product Videos”).  
   - The video is saved in a new database table and in the `/img/videoloops/` directory.  
   - Only one video can be uploaded per product.

2. **Setting the Video as the Product Cover**  
   - After being added, the **video automatically becomes the main cover** of the product. Changes are visible on the product page, the category page, and in the quick view modal.

3. **Front Office Integration**  
   - To display the video correctly in the PrestaShop Front Office, you need to manually modify the theme template files. Example files for the `classic` theme are provided in the `Classic theme modification` folder. Replace them accordingly and compile the .js files. This modification places a `<video>` element instead of `<img>` where the main product image would normally appear.

4. **Video Preview and Removal**  
   - The uploaded video can be viewed on the product edit page.  
   - A delete icon appears when a product has a video uploaded, allowing you to remove the video.

## Installation

1. Install the module in the Back Office (Modules → Module Manager → Install).
2. Replace the `classic` theme template files.
3. Replace the JavaScript files, compile them, then replace the `theme.js` file.
4. After installation, in **Products → Edit Product → “Description” tab**, a new section for uploading videos should be visible.

## Configuration

- Allowed file sizes and formats can be found in the `VideoCompoundType.php` file under the `constraints` section.
- You can change the video file storage path in the `LinkBuilderService.php` file.

## Notes and Further Development

- For non-standard themes, additional integration and adaptation of `.tpl` files (e.g., `product-cover-thumbnails.tpl`) may be necessary.

---

© 2025 Adam Mańko – [https://github.com/Maniek247](https://github.com/Maniek247)
