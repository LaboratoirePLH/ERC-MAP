UPDATE
    source
SET
    datation_id = NULL,
    est_datee = false
WHERE
    source.datation_id IN (
        SELECT
            DISTINCT datation.id
        FROM
            datation
        WHERE
            post_quem IS NULL
            AND ante_quem IS NULL
            AND commentaire_fr IS NULL
            AND commentaire_en IS NULL
    );

UPDATE
    attestation
SET
    datation_id = NULL,
    est_datee = false
WHERE
    attestation.datation_id IN (
        SELECT
            DISTINCT datation.id
        FROM
            datation
        WHERE
            post_quem IS NULL
            AND ante_quem IS NULL
            AND commentaire_fr IS NULL
            AND commentaire_en IS NULL
    );

DELETE FROM
    datation
WHERE
    post_quem IS NULL
    AND ante_quem IS NULL
    AND commentaire_fr IS NULL
    AND commentaire_en IS NULL;