-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE bib.Sp_SEL_Locales

AS
BEGIN
	
	SET NOCOUNT ON;

    SELECT ROW_NUMBER() OVER(ORDER BY l.iLocalId) AS N_,
		l.iLocalId,
		l.iFilId,
		l.iCodigoLocal,
		l.cDescriLocal,
		l.bHabilitado
	FROM bib.locales AS l
END
GO
