-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_estado_prestamos]
AS
BEGIN
	SET NOCOUNT ON

	SELECT ep.iEstadoPrestamoId,
		   ep.cDescriEstadoPrestamo
	FROM bib.estado_prestamos AS ep
	ORDER BY ep.iEstadoPrestamoId

	RETURN 1

END
GO
