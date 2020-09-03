-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_SEL_credenciales_rolXcBusqueda]
	@_cBusqueda VARCHAR(MAX)
AS
BEGIN
	SET NOCOUNT ON

	SELECT c.iCredId,
		   c.iPersId,
		   c.cCredUsuario,
		   p.cPersDocumento,
		   COALESCE(p.cPersPaterno,'')+' '+COALESCE(p.cPersMaterno,'')+' '+COALESCE(p.cPersNombre,'') AS cPersNombre,
		   c.cCredToken,
		   c.iCredIntentos,
		   c.cRol
	FROM seg.credenciales AS c
	LEFT OUTER JOIN grl.personas AS p ON c.iPersId=p.iPersId
	WHERE c.cCredUsuario LIKE '%'+@_cBusqueda+'%' OR
		  p.cPersDocumento LIKE '%'+@_cBusqueda+'%' OR
		  p.cPersPaterno+' '+p.cPersMaterno+' '+p.cPersNombre LIKE '%'+@_cBusqueda+'%'
	ORDER BY p.cPersPaterno,
			 p.cPersMaterno,
			 p.cPersNombre
	
	RETURN 1
END
GO
