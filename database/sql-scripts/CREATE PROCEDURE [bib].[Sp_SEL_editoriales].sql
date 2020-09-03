-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_editoriales]

AS
BEGIN
	SET NOCOUNT ON

	SELECT ROW_NUMBER() OVER(ORDER BY e.iEditorialId) AS N_,
		e.iEditorialId,
		e.cDescriEditorial,
		e.bHabilitado

	FROM bib.editoriales AS e
	
	RETURN 1

END
GO
