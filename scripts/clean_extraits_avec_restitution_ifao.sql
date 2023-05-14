-- Remove ifao style from <em> tags
UPDATE
    attestation
SET
    extrait_avec_restitution = REPLACE(
        extrait_avec_restitution,
        '<em style="font-family: ifaogreek;">',
        '<em>'
    )
WHERE
    extrait_avec_restitution LIKE '%ifao%';

-- Remove ifao style from <u> tags
UPDATE
    attestation
SET
    extrait_avec_restitution = REPLACE(
        extrait_avec_restitution,
        '<u style="font-family: ifaogreek;">',
        '<u>'
    )
WHERE
    extrait_avec_restitution LIKE '%ifao%';

-- Remove ifao style from <sup> tags
UPDATE
    attestation
SET
    extrait_avec_restitution = REPLACE(
        extrait_avec_restitution,
        '<sup style="font-family: ifaogreek;">',
        '<sup>'
    )
WHERE
    extrait_avec_restitution LIKE '%ifao%';

-- Remove ifao style from tags containing multiple styles
UPDATE
    attestation
SET
    extrait_avec_restitution = REPLACE(
        extrait_avec_restitution,
        ' font-family: ifaogreek;',
        ''
    )
WHERE
    extrait_avec_restitution LIKE '% font-family: ifaogreek;%';

UPDATE
    attestation
SET
    extrait_avec_restitution = REPLACE(
        extrait_avec_restitution,
        'font-family: ifaogreek; ',
        ''
    )
WHERE
    extrait_avec_restitution LIKE '%font-family: ifaogreek; %';

-- Remove all ifao <span> tags
UPDATE
    attestation
SET
    extrait_avec_restitution = REPLACE(
        REPLACE(
            extrait_avec_restitution,
            '<span style="font-family: ifaogreek;">',
            ''
        ),
        '</span>',
        ''
    )
WHERE
    extrait_avec_restitution LIKE '%ifao%';

-- Adds ifao where it is not present
UPDATE
    attestation
SET
    extrait_avec_restitution = CONCAT(
        '<span style="font-family: ifaogreek;">',
        extrait_avec_restitution,
        '</span>'
    )
WHERE
    extrait_avec_restitution NOT LIKE '%ifao%';