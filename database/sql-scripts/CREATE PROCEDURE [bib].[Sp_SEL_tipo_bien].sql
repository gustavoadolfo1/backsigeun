-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_tipo_bien]

AS
BEGIN

    SET NOCOUNT ON;

    SELECT tb.iTipoBienId,
        tb.cDescriTipoBien,
        tb.bHabilitado
    FROM bib.tipo_bien AS tb
    ORDER BY tb.iTipoBienId

    RETURN 1
END
GO
