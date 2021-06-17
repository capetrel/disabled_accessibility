(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.disabledAccessibility = {
    attach: function (context) {
      $('#disabled-accessibility-form', context).each(function () {

        let noBtns = $('.accessibility-no-buttons');
        if (noBtns.length !== 0 ) {
          return false;
        } else {
          // Variables
          let bodyClassList = document.body.classList;
          let body = $('body');
          let defaultLabels = $("input[id*='default'] + label");
          let cookieDuration = parseInt(drupalSettings.disabledAccessibilityCookieDuration);

          let InputDefaultTypoId = '#edit-typo-default-typo';
          let InputAdaptTypoId = '#edit-typo-adapt-typo';

          let InputDefaultAnimeId = '#edit-anime-default-anime';
          let InputDeactivateAnimeId = '#edit-anime-deactive-anime';

          let InputDefaultLineHeightId = '#edit-line-height-default-line-height';
          let InputHighLineHeightId = '#edit-line-height-high-line-height';

          let InputDefaultThemeId = '#edit-theme-default-theme';
          let InputDarkThemeId = '#edit-theme-dark-theme';

          let InputDefaultContrastId = '#edit-contrast-default-contrast';
          let InputEnforceContrastId = '#edit-contrast-enforce-contrast';
          let InputInverseContrastId = '#edit-contrast-inverse-contrast';

          let defaultTypo = $(InputDefaultTypoId);
          let adaptTypo = $(InputAdaptTypoId);

          let defaultAnime = $(InputDefaultAnimeId);
          let deactivateAnime = $(InputDeactivateAnimeId);

          let defaultLineHeight = $(InputDefaultLineHeightId);
          let HighLineHeight = $(InputHighLineHeightId);

          let defaultTheme = $(InputDefaultThemeId);
          let darkTheme = $(InputDarkThemeId);

          let defaultContrast = $(InputDefaultContrastId);
          let EnforceContrast = $(InputEnforceContrastId);
          let InverseContrast = $(InputInverseContrastId);

          // functions
          $.fn.switchBodyClasses = function (tagClassList, checkedToremove, classesToManage) {
            $($(this)).click(function (e) {
              e.preventDefault();
              $(this).attr('checked', true);
              $('#' + $(this).attr('id') + ' + label').addClass('is-on')
              for (let i = 0; i < checkedToremove.length; i++) {
                $(checkedToremove[i]).attr('checked', false);
                $(checkedToremove[i] + ' + label').removeClass('is-on');
              }
              if ($(this).attr('id').includes('default')) {
                for (let i = 0; i < classesToManage.length; i++) {
                  if (tagClassList.contains(classesToManage[i])) {
                    tagClassList.remove(classesToManage[i]);
                  }
                }
              } else {
                if (tagClassList.contains(classesToManage[0])) {
                  tagClassList.remove(classesToManage[0])
                }
                if (!tagClassList.contains(classesToManage[1])) {
                  tagClassList.add(classesToManage[1])
                }
              }
              $.cookie('eac', bodyClassList, { expires: cookieDuration  });
            })
          };

          let toggleAccessibilty = function (bodyClassesStr, classesNameStr, defaultInputElement, changeInputElement) {
            if (bodyClassesStr.includes(classesNameStr)) {
              changeInputElement.attr('checked', true);
              $('#'+changeInputElement.attr('id')+' + label').addClass('is-on');
            } else {
              defaultInputElement.attr('checked', true);
              $('#'+defaultInputElement.attr('id')+' + label').addClass('is-on');
            }
          }

          let removeDisabledInput = function (cookieClassesList, bodyClassesToRemove, InputIdsToCheck) {
            let newList = cookieClassesList;
            for (let i = 0; i < bodyClassesToRemove.length; i++) {
              if(cookieClassesList.includes(bodyClassesToRemove[i])) {
                let input = $(InputIdsToCheck[i]);
                if(input.length === 0) {
                  newList = cookieClassesList.replace(bodyClassesToRemove[i], '');
                }
              }
            }
            $.cookie('eac', newList.trim(), { expires: cookieDuration  });
            return newList;
          }

          // Initialisation and set Cookie
          if ($.cookie('eac')) {
            let cookieBodyClasses = $.cookie('eac');
            let newCookieBodyClasses = removeDisabledInput(
              cookieBodyClasses,
              ['deactive_anime', 'adapt_typo', 'high_line_height', 'dark_theme', 'enforce_contrast', 'inverse_contrast'],
              [InputDeactivateAnimeId, InputAdaptTypoId, InputHighLineHeightId, InputDarkThemeId, InputEnforceContrastId, InputInverseContrastId]
            );

            body.removeClass();
            body.addClass(newCookieBodyClasses);

            if( defaultAnime || deactivateAnime ) {
              toggleAccessibilty(cookieBodyClasses, 'deactive_anime', defaultAnime, deactivateAnime);
            }
            if( defaultTypo || adaptTypo ) {
              toggleAccessibilty(cookieBodyClasses, 'adapt_typo', defaultTypo, adaptTypo);
            }
            if( defaultLineHeight || HighLineHeight ) {
              toggleAccessibilty(cookieBodyClasses, 'high_line_height', defaultLineHeight, HighLineHeight);
            }
            if( defaultTheme || darkTheme ) {
              toggleAccessibilty(cookieBodyClasses, 'dark_theme', defaultTheme, darkTheme);
            }
            if( defaultContrast || EnforceContrast || InverseContrast ) {
              if (cookieBodyClasses.includes('enforce_contrast')) {
                EnforceContrast.attr('checked', true);
                $('#'+EnforceContrast.attr('id')+' + label').addClass('is-on');
              }else if (cookieBodyClasses.includes('inverse_contrast')){
                InverseContrast.attr('checked', true);
                $('#'+InverseContrast.attr('id')+' + label').addClass('is-on');
              }
              else {
                defaultContrast.attr('checked', true);
                $('#'+defaultContrast.attr('id')+' + label').addClass('is-on');
              }
            }

          } else {
            $.cookie('eac', bodyClassList, { expires: cookieDuration });

            defaultLabels.addClass('is-on');

            if(defaultAnime){
              defaultAnime.attr('checked', true);
            }
            if(defaultTypo){
              defaultTypo.attr('checked', true);
            }
            if(defaultLineHeight){
              defaultLineHeight.attr('checked', true);
            }
            if(defaultTheme){
              defaultTheme.attr('checked', true);
            }
            if(defaultContrast){
              defaultContrast.attr('checked', true);
            }
          }

          // Toggle classes and checkeds
          if( defaultAnime || deactivateAnime ) {
            defaultAnime.switchBodyClasses(bodyClassList,  [InputDeactivateAnimeId], ['deactive_anime', '']);
            deactivateAnime.switchBodyClasses(bodyClassList, [InputDefaultAnimeId], ['', 'deactive_anime']);
          }
          if( defaultTypo || adaptTypo ) {
            defaultTypo.switchBodyClasses(bodyClassList, [InputAdaptTypoId], ['adapt_typo', '']);
            adaptTypo.switchBodyClasses(bodyClassList, [InputDefaultTypoId], ['', 'adapt_typo']);
          }
          if( defaultLineHeight || HighLineHeight ) {
            defaultLineHeight.switchBodyClasses(bodyClassList, [InputHighLineHeightId], ['high_line_height', '']);
            HighLineHeight.switchBodyClasses(bodyClassList, [InputDefaultLineHeightId], ['', 'high_line_height']);
          }
          if( defaultTheme || darkTheme ) {
            defaultTheme.switchBodyClasses(bodyClassList, [InputDarkThemeId], ['dark_theme', '']);
            darkTheme.switchBodyClasses(bodyClassList, [InputDefaultThemeId], ['', 'dark_theme']);
          }
          if( defaultContrast || EnforceContrast || InverseContrast ) {
            defaultContrast.switchBodyClasses(bodyClassList, [InputInverseContrastId, InputEnforceContrastId], ['inverse_contrast', 'enforce_contrast'] );
            EnforceContrast.switchBodyClasses(bodyClassList, [InputInverseContrastId, InputDefaultContrastId], ['inverse_contrast', 'enforce_contrast'] );
            InverseContrast.switchBodyClasses(bodyClassList, [InputDefaultContrastId, InputEnforceContrastId], ['enforce_contrast', 'inverse_contrast'] );
          }
        }

      })
    }
  };

}(jQuery, Drupal, drupalSettings));
