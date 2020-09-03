-- ================================================
USE SIGEUNBB
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_autores]

AS
BEGIN
	SET NOCOUNT ON

	SELECT ROW_NUMBER() OVER(ORDER BY a.iAutorId) AS N_,
		   a.iAutorId,
		   a.cNombreAutores
	FROM bib.autores AS a
	
	RETURN 1
END
GO
