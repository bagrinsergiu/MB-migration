# DWRB-auto Task: Aurora - content not loading

## Источник
- GitHub Issue: #847 — https://github.com/bagrinsergiu/MB-Support/issues/847
- Labels: 
- State: OPEN

## Исходная формулировка
Nothing is loading on this page: https://marmota751b4a5.brizy.site/membership

See clover: https://c3forchrist.org/member/membership

## Контекст из комментариев
**golovencoserghei** (2026-01-22T14:32:44Z):
fixed

<img width="1379" height="681" alt="Image" src="https://github.com/user-attachments/assets/0568fbc7-23f3-40a9-bb4c-7e6d0fcfe1bd" />

**rivkahmagnus** (2026-01-28T23:55:50Z):
@golovencoserghei Thanks! I'm seeing the page load now but also a few issues: https://marmota751b4a5.brizy.site/membership

- This extra section/block doesn't show on the clover site: https://prnt.sc/HBKHDwP-Bg95
- The spacing down the left side of the page is too tight and doesn't match the spacing on the right: https://prnt.sc/-7WC0J9hlYZK
- Titles 1 and 2 on the page aren't showing bolded like 3 and 4: https://prnt.sc/j79zbXSmtucF

**golovencoserghei** (2026-01-29T15:33:31Z):
@nicolaisirghi  

> Titles 1 and 2 on the page aren't showing bolded like 3 and 4: https://prnt.sc/j79zbXSmtucF

<img width="1285" height="1204" alt="Image" src="https://github.com/user-attachments/assets/b2d84932-2b6f-43e7-899d-b883acee4d0d" />

**golovencoserghei** (2026-01-29T15:50:25Z):
fixed

> This extra section/block doesn't show on the clover site: https://prnt.sc/HBKHDwP-Bg95
> The spacing down the left side of the page is too tight and doesn't match the spacing on the right: https://prnt.sc/-7WC0J9hlYZK

<img width="1449" height="1064" alt="Image" src="https://github.com/user-attachments/assets/d6d30df1-5f79-4dd6-b08d-00cdb82b11aa" />

**nicolaisirghi** (2026-01-30T08:26:21Z):
> [@nicolaisirghi](https://github.com/nicolaisirghi)
> 
> > Titles 1 and 2 on the page aren't showing bolded like 3 and 4: https://prnt.sc/j79zbXSmtucF
> 
> <img alt="Image" width="1285" height="1204" src="https://private-user-images.githubusercontent.com/12428787/542286127-b2d84932-2b6f-43e7-899d-b883acee4d0d.png?jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3Njk3NjE3MzEsIm5iZiI6MTc2OTc2MTQzMSwicGF0aCI6Ii8xMjQyODc4Ny81NDIyODYxMjctYjJkODQ5MzItMmI2Zi00M2U3LTg5OWQtYjg4M2FjZWU0ZDBkLnBuZz9YLUFtei1BbGdvcml0aG09QVdTNC1ITUFDLVNIQTI1NiZYLUFtei1DcmVkZW50aWFsPUFLSUFWQ09EWUxTQTUzUFFLNFpBJTJGMjAyNjAxMzAlMkZ1cy1lYXN0LTElMkZzMyUyRmF3czRfcmVxdWVzdCZYLUFtei1EYXRlPTIwMjYwMTMwVDA4MjM1MVomWC1BbXotRXhwaXJlcz0zMDAmWC1BbXotU2lnbmF0dXJlPTgwMzAzMTI0MzYxYjNlMzE4MjcyMWYwNDdhZWYyNDNiYTRmMzBiODE5OWEyN2E3ZmVhMGM2OWZhYjUyM2QwMTYmWC1BbXotU2lnbmVkSGVhZGVycz1ob3N0In0.-wFaJ6uSKtr0B9L5RbzlXZRdB2FOavKI9qFbHajtAIc">

@golovencoserghei , For the first two titles they have another font-family, and You didn't load the `font-weight:500` for them, so the browser can't apply the bold

<img width="2122" height="475" alt="Image" src="https://github.com/user-attachments/assets/ecf87117-9c26-4f0a-bd27-464358414557" />

<img width="859" height="871" alt="Image" src="https://github.com/user-attachments/assets/991fd260-345d-44bc-91b5-07f88e8fcbe4" />

**rivkahmagnus** (2026-02-01T16:30:11Z):
@golovencoserghei Can you let me know where I can view the updates for 1 and 2 from [this list](https://github.com/bagrinsergiu/MB-Support/issues/847#issuecomment-3814551431)? I'm not seeing them here: https://marmota751b4a5.brizy.site/membership

I also reviewed this page on mobile, and I'm still seeing the additional section/block on the top of the page on mobile: https://prnt.sc/2wAofa40Ava2, but also, each section down the page is showing a gap in between: https://prnt.sc/2VfcUWK8ms5X.

**golovencoserghei** (2026-02-03T12:24:10Z):
Yes, there was a problem with normalizing names for the font, but now it is loading.  **Fixed**

Please take a look at this as well



<img width="848" height="512" alt="Image" src="https://github.com/user-attachments/assets/c877a83b-3724-4bff-bae2-5c8d0d4e0419" />

Here I am submitting all the fonts for this page. 

<img width="750" height="384" alt="Image" src="https://github.com/user-attachments/assets/9946ca04-e9af-43ed-8fd3-a2fd61ff283f" />

Here you can see that they are loaded and present in the project. 

<img width="969" height="862" alt="Image" src="https://github.com/user-attachments/assets/884d51cf-c9e3-4974-8695-097e53718e9d" />

Here you can see that the required font is not being applied.

<img width="1150" height="456" alt="Image" src="https://github.com/user-attachments/assets/39f20749-cd0d-4487-8c95-0c3c649460a2" /> 


**golovencoserghei** (2026-02-10T13:03:17Z):
fixed 
update https://marmota751b4a5.brizy.site/membership


**rivkahmagnus** (2026-02-24T04:01:20Z):
@golovencoserghei Hey, I'm still not seeing the first two headings in the bold style: https://prnt.sc/_xrpmtYna1_Z

 https://marmota751b4a5.brizy.site/membership

**rivkahmagnus** (2026-03-10T03:44:38Z):
@golovencoserghei Would this styling just need to be adjusted manually? Let me know if that's the case, and we can close this issue. Thanks!

## Проблемы / Баги (извлечённые)
*(извлеки из исходной формулировки)*

## Ожидаемый результат
*(извлеки из исходной формулировки)*

## Как проверить
*(укажи команды/тесты для проверки)*

## Контекст (модули, файлы)
*(если видно из issue)*
