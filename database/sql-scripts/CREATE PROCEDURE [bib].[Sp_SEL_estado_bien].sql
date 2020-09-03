-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_estado_bien]

AS
BEGIN
	SET NOCOUNT ON

	SELECT eb.iEstadoBienId,
		   eb.cDescriEstadoBien,
		   eb.bHabilitado
	FROM bib.estado_bien AS eb
	ORDER BY eb.iEstadoBienId

	RETURN 1
END
GO
