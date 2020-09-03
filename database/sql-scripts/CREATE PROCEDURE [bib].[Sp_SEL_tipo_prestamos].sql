-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_tipo_prestamos]

AS
BEGIN
	SET NOCOUNT ON

	SELECT tp.iTipoPrestamoId,
		   tp.cDescriTipoPrestamo
	FROM bib.tipo_prestamos AS tp
	ORDER BY tp.iTipoPrestamoId

	RETURN 1
END
GO
-- ================================================
